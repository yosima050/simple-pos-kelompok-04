<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessProductImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductImportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = $request->file('file')->store('imports');

        ProcessProductImport::dispatch($path);

        return back()->with('success', 'Import sedang diproses di background.');
    }
}
