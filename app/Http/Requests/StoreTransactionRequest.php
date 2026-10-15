<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $items = $this->input('items', []);

            if (!is_array($items)) {
                return;
            }

            foreach ($items as $index => $item) {
                if (!empty($item['product_id']) && isset($item['qty'])) {
                    $product = Product::find($item['product_id']);

                    if ($product && (int) $item['qty'] > $product->stock) {
                        $message = "Stok produk '{$product->name}' tidak mencukupi (sisa: {$product->stock}, diminta: {$item['qty']}).";
                        $validator->errors()->add("items.{$index}.qty", $message);
                        $validator->errors()->add('items', $message);
                    }
                }
            }
        });
    }
}