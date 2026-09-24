<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat 1 user untuk data transaksi
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Membuat 4 kategori produk
        $categories = collect([
            'Makanan',
            'Minuman',
            'Snack',
            'Lainnya',
        ])->mapWithKeys(function ($name) {
            $category = Category::create([
                'name' => $name,
            ]);

            return [$name => $category->id];
        });

        // Membuat 300 produk
        $products = [];

        foreach ($categories as $categoryId) {
            for ($i = 1; $i <= 75; $i++) {
                $products[] = [
                    'category_id' => $categoryId,
                    'name' => fake()->words(3, true),
                    'price' => fake()->numberBetween(5000, 100000),
                    'stock' => fake()->numberBetween(0, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert produk secara batch 50 data
        foreach (array_chunk($products, 50) as $chunk) {
            DB::table('products')->insert($chunk);
        }

        // Ambil ID dan harga produk
        $productPrices = DB::table('products')
            ->pluck('price', 'id');

        // Membuat 2500 transaksi
        for ($i = 1; $i <= 2500; $i++) {
            DB::transaction(function () use ($user, $productPrices) {
                $transactionId = DB::table('transactions')->insertGetId([
                    'user_id' => $user->id,
                    'total' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Setiap transaksi memiliki 1-4 produk
                $numberOfItems = fake()->numberBetween(1, 4);

                $selectedProducts = $productPrices
                    ->keys()
                    ->random($numberOfItems);

                $total = 0;

                foreach ($selectedProducts as $productId) {
                    $qty = fake()->numberBetween(1, 5);
                    $price = $productPrices[$productId];
                    $subtotal = $qty * $price;

                    DB::table('transaction_details')->insert([
    'transaction_id' => $transactionId,
    'product_id' => $productId,
    'qty' => $qty,
    'subtotal' => $subtotal,
    'created_at' => now(),
    'updated_at' => now(),
]);

                    $total += $subtotal;
                }

                DB::table('transactions')
                    ->where('id', $transactionId)
                    ->update([
                        'total' => $total,
                        'updated_at' => now(),
                    ]);
            });
        }
    }
}