<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductImportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');

        if ($handle === false) {
            return back()->with('error', 'File CSV tidak dapat dibaca.');
        }

        $header = fgetcsv($handle);

        if ($header === false) {
            fclose($handle);

            return back()->with('error', 'File CSV kosong.');
        }

        $header = array_map(
            fn ($value) => strtolower(trim((string) $value)),
            $header
        );

        // Menghapus BOM UTF-8 jika ada pada kolom pertama.
       $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        $requiredHeaders = ['sku', 'name', 'category_id', 'price', 'stock'];

        if (array_diff($requiredHeaders, $header)) {
            fclose($handle);

            return back()->with(
                'error',
                'Header CSV harus memuat: sku, name, category_id, price, stock.'
            );
        }

        $success = 0;
        $failed = 0;
        $rowNumber = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count($row) !== count($header)) {
                $failed++;
                continue;
            }

            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'sku' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:255'],
                'category_id' => ['required', 'integer', 'exists:categories,id'],
                'price' => ['required', 'integer', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
            ]);

            if ($validator->fails()) {
                $failed++;
                continue;
            }

            try {
                Product::updateOrCreate(
                    ['sku' => trim($data['sku'])],
                    [
                        'name' => trim($data['name']),
                        'category_id' => (int) $data['category_id'],
                        'price' => (int) $data['price'],
                        'stock' => (int) $data['stock'],
                    ]
                );

                $success++;
            } catch (\Throwable $e) {
                report($e);
                $failed++;
            }
        }

        fclose($handle);

        return redirect()
            ->route('products.index')
            ->with('success', "Impor selesai. Berhasil: {$success} baris; gagal/dilewati: {$failed} baris.");
    }
}
