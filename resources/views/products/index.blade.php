@extends('layouts.app')

@section('title', 'Produk')

@section('content')

<h1 class="text-lg font-semibold mb-4">Daftar Produk</h1>

@if (session('success'))
    <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-50 text-red-700 p-3 rounded-md mb-4">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-50 text-red-700 p-3 rounded-md mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<a href="{{ route('products.create') }}"
   class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded-md">
    Tambah Produk
</a>

<div class="border rounded-md p-4 mb-6">
    <h2 class="font-semibold mb-2">Import Produk dari CSV</h2>

    <p class="text-sm text-gray-600 mb-3">
        Pilih file CSV dengan kolom sku, name, category_id, price, dan stock.
    </p>

   <form action="{{ route('products.import') }}"
      method="POST"
      enctype="multipart/form-data"
      class="flex flex-wrap items-center gap-3">
    @csrf

    <input type="file"
           name="file"
           accept=".csv,.txt"
           required
           class="border rounded-md p-2">

       <button type="submit"
        style="background-color: #15803d; color: white; padding: 10px 16px; border-radius: 6px; border: none;">
        Import CSV
    </button>
</form>
</div>

<table class="w-full text-left border-collapse">
    <thead>
        <tr class="border-b">
            <th class="py-2 pr-4">Nama</th>
            <th class="py-2 pr-4">Kategori</th>
            <th class="py-2 pr-4">Harga</th>
            <th class="py-2 pr-4">Stok</th>
            <th class="py-2 pr-4">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($products as $product)
            <tr class="border-b">
                <td class="py-2 pr-4">{{ $product->name }}</td>
                <td class="py-2 pr-4">{{ $product->category->name }}</td>
                <td class="py-2 pr-4">Rp {{ number_format($product->price) }}</td>
                <td class="py-2 pr-4">{{ $product->stock }}</td>
                <td class="py-2 pr-4">
                    <a href="{{ route('products.edit', $product->id) }}"
                       class="inline-block bg-yellow-500 text-white px-3 py-1 rounded-md">
                        Edit
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-4 text-center text-gray-500">
                    Belum ada produk.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $products->links() }}
</div>

@endsection
