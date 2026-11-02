@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl p-6">
    <h1 class="mb-6 text-2xl font-bold">Import Produk CSV</h1>

    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 p-3 text-red-800">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mb-8 rounded border p-4">
        <form action="{{ route('imports.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <label for="file" class="mb-2 block font-medium">
                Pilih file CSV
            </label>

            <input
                id="file"
                type="file"
                name="file"
                accept=".csv,.txt"
                required
                class="mb-4 block w-full rounded border p-2"
            >

            <p class="mb-4 text-sm text-gray-600">
                Format kolom: category_id, name, price, stock.
                Baris pertama digunakan sebagai header.
            </p>

            <button type="submit"
                    class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Import CSV
            </button>
        </form>
    </div>

    <h2 class="mb-3 text-xl font-semibold">Riwayat Error Import</h2>

    @if ($errors->isNotEmpty())
        <p class="mb-3 text-sm text-gray-600">
            Berikut pesan validasi form. Lihat tabel di bawah untuk error per baris CSV.
        </p>
    @endif

    @if ($importErrors->isEmpty())
        <p class="rounded border p-4 text-gray-600">
            Belum ada baris CSV yang gagal diimpor.
        </p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-3 text-left">Nomor Baris CSV</th>
                        <th class="border p-3 text-left">Pesan Error</th>
                        <th class="border p-3 text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($importErrors as $error)
                        <tr>
                            <td class="border p-3">
                                {{ $error->row_number }}
                            </td>
                            <td class="border p-3">
                                {{ $error->error_message }}
                            </td>
                            <td class="border p-3">
                                {{ $error->created_at }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
