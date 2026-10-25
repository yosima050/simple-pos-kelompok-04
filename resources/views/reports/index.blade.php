
@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Laporan Penjualan</h1>

    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label for="from" class="block mb-1">Dari</label>
            <input
                type="date"
                id="from"
                name="from"
                value="{{ $from }}"
                class="border rounded px-3 py-2"
            >
        </div>

        <div>
            <label for="to" class="block mb-1">Sampai</label>
            <input
                type="date"
                id="to"
                name="to"
                value="{{ $to }}"
                class="border rounded px-3 py-2"
            >
        </div>

        <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2">
            Filter
        </button>

        <a
            href="{{ route('reports.export.csv', request()->query()) }}"
            class="bg-green-600 text-white rounded px-4 py-2"
        >
            Export CSV
        </a>

        <a
            href="{{ route('reports.export.xlsx', request()->query()) }}"
            class="bg-emerald-700 text-white rounded px-4 py-2"
        >
            Export Excel
        </a>
    </form>

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-semibold">Total Penjualan</h2>

        <p class="text-2xl font-bold mt-2">
            Rp {{ number_format($totalPenjualan) }}
        </p>

        <p class="text-gray-600 mt-1">
            {{ $jumlahTransaksi }} transaksi
        </p>
    </div>
@endsection