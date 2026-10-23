<nav class="bg-slate-900 text-white px-4 py-3 flex gap-4 items-center">
    <span class="font-semibold">Simple POS</span>

    <a href="{{ route('pos.create') }}"
       class="hover:underline {{ request()->routeIs('pos.create') ? 'text-amber-400 font-semibold underline' : '' }}">
        Kasir
    </a>

    <a href="{{ route('transactions.index') }}"
       class="hover:underline {{ request()->routeIs('transactions.index') ? 'text-amber-400 font-semibold underline' : '' }}">
        Transaksi
    </a>

    @if (auth()->user()?->isAdmin())
        <a href="{{ route('products.index') }}"
           class="hover:underline {{ request()->routeIs('products.*') ? 'text-amber-400 font-semibold underline' : '' }}">
            Produk
        </a>

        <a href="{{ route('categories.index') }}"
           class="hover:underline {{ request()->routeIs('categories.*') ? 'text-amber-400 font-semibold underline' : '' }}">
            Kategori
        </a>
        
        <a href="{{ route('reports.index') }}"
           class="hover:underline {{ request()->routeIs('reports.*') ? 'text-amber-400 font-semibold underline' : '' }}">
            Laporan
        </a>
    @endif

    <span class="ml-auto text-sm">
        {{ auth()->user()?->name }} ({{ auth()->user()?->role }})
    </span>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="hover:underline">Keluar</button>
    </form>
</nav>