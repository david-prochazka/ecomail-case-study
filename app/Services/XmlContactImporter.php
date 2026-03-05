<?php

namespace App\Services;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;
use XMLReader;

class XmlContactImporter
{
    private const int BATCH_SIZE = 500;

    public function __construct(
        private EmailValidator $validator = new EmailValidator(),
    ) {}

    public function import(string $filePath): array
    {
        $start = microtime(true);

        $total = 0;
        $imported = 0;
        $duplicates = 0;
        $invalid = 0;
        $errors = [];
        $batch = [];

        $rfcValidation = new RFCValidation();

        $reader = new XMLReader();
        $reader->open($filePath);

        while ($reader->read()) {
            if ($reader->nodeType !== XMLReader::ELEMENT || $reader->name !== 'item') {
                continue;
            }

            $node = new SimpleXMLElement($reader->readOuterXml());

            $email = trim((string) $node->email);
            $firstName = trim((string) $node->first_name);
            $lastName = trim((string) $node->last_name);

            $total++;

            if (!$this->validator->isValid($email, $rfcValidation)) {
                $invalid++;
                $errors[] = "Item {$total}: invalid e-mail '{$email}'";
                continue;
            }

            if (empty($firstName) || empty($lastName)) {
                $invalid++;
                $errors[] = "Item {$total}: missing first name or last name";
                continue;
            }

            $batch[] = [
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= self::BATCH_SIZE) {
                $importedBatch = $this->insertBatch($batch);
                $imported += $importedBatch['imported'];
                $duplicates += $importedBatch['duplicates'];
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $importedBatch = $this->insertBatch($batch);
            $imported += $importedBatch['imported'];
            $duplicates += $importedBatch['duplicates'];
        }

        $reader->close();

        $time = round(microtime(true) - $start, 2);

        return [
            'total' => $total,
            'imported' => $imported,
            'duplicates' => $duplicates,
            'invalid' => $invalid,
            'time' => $time,
            'errors' => $errors,
        ];
    }

    private function insertBatch(array &$batch): array
    {
        return DB::transaction(function () use ($batch) {
            $emails = array_column($batch, 'email');

            $existing = DB::table('contacts')
                ->whereIn('email', $emails)
                ->pluck('email')
                ->map(fn ($e) => strtolower($e))
                ->flip()
                ->all();

            $toInsert = [];
            $duplicateCount = 0;

            foreach ($batch as $row) {
                if (isset($existing[strtolower($row['email'])])) {
                    $duplicateCount++;
                } else {
                    $toInsert[] = $row;
                    $existing[strtolower($row['email'])] = true;
                }
            }

            if ($toInsert) {
                DB::table('contacts')->insert($toInsert);
            }

            return [
                'imported' => count($toInsert),
                'duplicates' => $duplicateCount,
            ];
        });
    }
}
