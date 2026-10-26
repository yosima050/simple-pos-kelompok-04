<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcessProductImport implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public array $backoff = [10, 60];
    public int $timeout = 60;

    public function __construct(private string $csvPath) {}

    public function handle(): void
    {
        $filePath = Storage::path($this->csvPath);

        if (! file_exists($filePath)) {
            return;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (empty($lines)) {
            Storage::delete($this->csvPath);
            return;
        }

        $rows = array_map('str_getcsv', $lines);
        $header = array_shift($rows);

        if (! $header) {
            Storage::delete($this->csvPath);
            return;
        }

        // Normalisasi header dan bersihkan karakter BOM UTF-8 jika ada
        $header = array_map(fn ($val) => strtolower(trim((string) $val)), $header);
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        $hasSku = in_array('sku', $header);
        $hasStock = in_array('stock', $header);

        foreach ($rows as $row) {
            if (count($row) !== count($header)) {
                continue;
            }

            $rowCombined = array_combine($header, $row);

            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'category_id' => ['required', 'integer', 'exists:categories,id'],
                'price' => ['required', 'integer', 'min:0'],
            ];

            if ($hasSku) {
                $rules['sku'] = ['nullable', 'string', 'max:255'];
            }
            if ($hasStock) {
                $rules['stock'] = ['nullable', 'integer', 'min:0'];
            }

            $validator = Validator::make($rowCombined, $rules);

            if ($validator->fails()) {
                continue;
            }

            $data = $validator->validated();

            if (!empty($data['sku'])) {
                Product::updateOrCreate(
                    ['sku' => trim($data['sku'])],
                    [
                        'name' => trim($data['name']),
                        'category_id' => (int) $data['category_id'],
                        'price' => (int) $data['price'],
                        'stock' => isset($data['stock']) ? (int) $data['stock'] : 0,
                    ]
                );
            } else {
                Product::updateOrCreate(
                    [
                        'name' => trim($data['name']),
                        'category_id' => (int) $data['category_id'],
                    ],
                    [
                        'price' => (int) $data['price'],
                        'stock' => isset($data['stock']) ? (int) $data['stock'] : 0,
                    ]
                );
            }
        }

        Storage::delete($this->csvPath);
    }
}
