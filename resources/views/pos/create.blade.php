
@extends('layouts.app')

@section('title', 'Kasir')

@section('content')

<h1 class="text-lg font-semibold mb-4">Transaksi Kasir</h1>

@if (session('success'))
    <div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

@error('items')
    <div class="bg-red-50 text-red-700 p-3 rounded-md mb-4">
        {{ $message }}
    </div>
@enderror

<form method="POST" action="{{ route('transactions.store') }}"
    x-data="{
        cart: [],
        selectedProductId: null,

        addToCart(id, name, price) {
            const existingItem = this.cart.find(item => item.id === id);

            if (existingItem) {
                existingItem.qty += 1;
            } else {
                this.cart.push({
                    id: id,
                    name: name,
                    price: price,
                    qty: 1
                });
            }
        },

        removeFromCart(id) {
            const index = this.cart.findIndex(item => item.id === id);

            if (index > -1) {
                this.cart.splice(index, 1);
            }
        },

        subtotal() {
            return this.cart.reduce(
                (sum, item) => sum + (item.price * item.qty),
                0
            );
        }
    }">

    @csrf

    {{-- Daftar Produk --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($products as $product)
            <div
                class="border rounded-md p-3 cursor-pointer hover:bg-gray-50 transition"
                :class="selectedProductId === {{ $product->id }}
                    ? 'ring-2 ring-blue-500'
                    : ''"
                @click="
                    selectedProductId = {{ $product->id }};
                    addToCart(
                        {{ $product->id }},
                        {{ Js::from($product->name) }},
                        {{ $product->price }}
                    )
                "
            >
                <p class="font-medium">{{ $product->name }}</p>

                @if ($product->stock < 10)
                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded">
                        Stok Menipis
                    </span>
                @endif

                <p class="text-sm text-slate-500 mt-1">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    {{-- Keranjang --}}
    <div class="mt-4 border-t pt-3">
        <h2 class="font-semibold mb-3">Keranjang Belanja</h2>

        <p x-show="cart.length === 0"
            class="text-sm text-gray-500 mb-3">
            Belum ada produk di keranjang.
        </p>

        <template x-for="item in cart" :key="item.id">
            <div class="flex justify-between items-center gap-4 text-sm mb-3 border-b pb-3">
                <div class="flex-1">
                    <p class="font-medium" x-text="item.name"></p>

                    <p class="text-gray-500 mt-1">
                        Rp <span x-text="item.price.toLocaleString('id-ID')"></span>
                        × <span x-text="item.qty"></span>
                    </p>

                    <p class="font-semibold mt-1">
                        Rp
                        <span x-text="(item.price * item.qty).toLocaleString('id-ID')"></span>
                    </p>
                </div>

                <button type="button"
                    @click="removeFromCart(item.id)"
                    class="text-red-500 hover:text-red-700 font-medium">
                    Hapus
                </button>

                {{-- Data untuk transaksi di server --}}
                <input type="hidden"
                    :name="'items[' + cart.indexOf(item) + '][product_id]'"
                    :value="item.id">

                <input type="hidden"
                    :name="'items[' + cart.indexOf(item) + '][qty]'"
                    :value="item.qty">
            </div>
        </template>

        {{-- Subtotal --}}
        <div class="flex justify-between items-center mt-3 font-semibold">
            <span>Subtotal</span>
            <span>
                Rp <span x-text="subtotal().toLocaleString('id-ID')"></span>
            </span>
        </div>

        <button type="submit"
            class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
            Bayar
        </button>
    </div>
</form>

@endsection