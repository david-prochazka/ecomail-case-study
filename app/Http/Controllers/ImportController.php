<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessContactsImport;
use App\Models\Import;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('import.form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xml',
        ]);

        $path = $request->file('file')->store('imports');

        $import = Import::create([
            'file' => Storage::path($path),
            'status' => 'pending',
        ]);

        ProcessContactsImport::dispatch($import);

        return redirect()->route('import.status', $import);
    }

    public function status(Import $import)
    {
        return view('import.report', compact('import'));
    }
}
