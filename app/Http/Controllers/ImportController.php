<?php

namespace App\Http\Controllers;

use App\Models\ImportError;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ImportController extends Controller
{
    public function index(): View
    {
        $importErrors = ImportError::latest()->get();

        return view('imports.index', compact('importErrors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        // Hapus catatan error dari proses import sebelumnya.
        ImportError::query()->delete();

        $handle = fopen(
            $request->file('file')->getRealPath(),
            'r'
        );

        if ($handle === false) {
            return back()->withErrors([
                'file' => 'File CSV tidak dapat dibaca.',
            ]);
        }

        $rowNumber = 0;
        $successCount = 0;

        try {
            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                $rowNumber++;

                // Baris pertama adalah header CSV.
                if ($rowNumber === 1) {
                    continue;
                }

                // Lewati baris yang kosong.
                if (count(array_filter(
                    $row,
                    fn ($value) => trim((string) $value) !== ''
                )) === 0) {
                    continue;
                }

                try {
                    if (count($row) !== 4) {
                        throw new \RuntimeException(
                            'Jumlah kolom harus 4: category_id, name, price, stock.'
                        );
                    }

                    [
                        $categoryId,
                        $name,
                        $price,
                        $stock
                    ] = array_map(
                        fn ($value) => trim((string) $value),
                        $row
                    );

                    $data = [
                        'category_id' => $categoryId,
                        'name' => $name,
                        'price' => $price,
                        'stock' => $stock,
                    ];

                    $validator = Validator::make($data, [
                        'category_id' => [
                            'required',
                            'integer',
                            'exists:categories,id',
                        ],
                        'name' => [
                            'required',
                            'string',
                            'max:255',
                        ],
                        'price' => [
                            'required',
                            'integer',
                            'min:0',
                        ],
                        'stock' => [
                            'required',
                            'integer',
                            'min:0',
                        ],
                    ]);

                    if ($validator->fails()) {
                        throw new \RuntimeException(
                            implode(
                                ' ',
                                $validator->errors()->all()
                            )
                        );
                    }

                    Product::create($data);

                    $successCount++;
                } catch (\Throwable $e) {
                    ImportError::create([
                        'row_number' => $rowNumber,
                        'error_message' => $e->getMessage(),
                    ]);
                }
            }
        } finally {
            fclose($handle);
        }

        $errorCount = ImportError::count();

        return redirect()
            ->route('imports.index')
            ->with(
                'success',
                "Import selesai. {$successCount} produk berhasil diimpor dan {$errorCount} baris gagal."
            );
    }
}
