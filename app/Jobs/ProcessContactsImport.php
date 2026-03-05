<?php

namespace App\Jobs;

use App\Models\Import;
use App\Services\XmlContactImporter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessContactsImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Import $import
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(XmlContactImporter $importer): void
    {
        $this->import->update(['status' => 'processing']);

        try {
            $result = $importer->import($this->import->file);

            $this->import->update([
                'status' => 'completed',
                'total' => $result['total'],
                'imported' => $result['imported'],
                'duplicates' => $result['duplicates'],
                'invalid' => $result['invalid'],
                'time' => $result['time'],
                'errors' => $result['errors'],
            ]);
        } catch (\Throwable $e) {
            $this->import->update([
                'status' => 'failed',
                'errors' => [$e->getMessage()],
            ]);
        }
    }
}
