<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function create()
    {
        // Mengambil seluruh produk yang stock-nya di atas 0 dari database
        $products = Product::where('stock', '>', 0)->get();

        return view('pos.create', ['products' => $products]);
    }

    public function store()
    {
        return 'Transaksi disimpan (belum ada logika penyimpanan)';
    }

    public function index()
    {
        $transactions = Transaction::latest()->paginate(15);

        return view('transactions.index', compact('transactions'));
    }

    public function show(string $id)
    {
        return "Detail transaksi #{$id}";
    }
}