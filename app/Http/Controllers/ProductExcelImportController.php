<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductExcelImportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        Excel::import(
            new ProductsImport,
            $request->file('file')
        );

        return back()->with('success', 'Import Excel berhasil diproses.');
    }
}