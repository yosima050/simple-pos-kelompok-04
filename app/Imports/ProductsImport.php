<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    public function model(array $row)
    {
        $validator = Validator::make($row, [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'sku' => ['nullable', 'string', 'max:255'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return null;
        }

        $data = $validator->validated();

        $values = [
            'name' => trim($data['name']),
            'category_id' => (int) $data['category_id'],
            'price' => (int) $data['price'],
            'stock' => isset($data['stock']) ? (int) $data['stock'] : 0,
        ];

        if (! empty($data['sku'])) {
            return Product::updateOrCreate(
                ['sku' => trim($data['sku'])],
                $values
            );
        }

        return Product::updateOrCreate(
            [
                'name' => $values['name'],
                'category_id' => $values['category_id'],
            ],
            [
                'price' => $values['price'],
                'stock' => $values['stock'],
            ]
        );
    }

    public function chunkSize(): int
    {
        return 100;
    }
}