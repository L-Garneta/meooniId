<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') | Meooni.id</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">

    <div class="min-h-screen md:flex">

        <!-- Sidebar -->
        <aside class="w-full bg-white border-r border-gray-200 md:w-64 md:min-h-screen">
            <div class="border-b border-gray-200 px-6 py-5">
                <a href="{{ route('admin.dashboard') }}"
                    class="text-2xl font-bold text-pink-600">
                    Meooni<span class="text-gray-800">.id</span>
                </a>
                <p class="mt-1 text-xs text-gray-500">Admin Panel</p>
            </div>

            <nav class="space-y-1 p-4">
                <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Menu Utama
                </p>

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center rounded-lg bg-pink-50 px-3 py-2.5 font-medium text-pink-700">
                    Dashboard
                </a>

                <div class="px-3 pt-4 pb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Manajemen Toko
                </div>

                <a href="{{ route('admin.products.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-gray-600 hover:bg-gray-100">
                    Produk
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-gray-600 hover:bg-gray-100">
                    Kategori
                </a>

                <a href="#pesanan"
                    class="block rounded-lg px-3 py-2.5 text-gray-600 hover:bg-gray-100">
                    Antrian Pesanan
                </a>

                <a href="#pembayaran"
                    class="block rounded-lg px-3 py-2.5 text-gray-600 hover:bg-gray-100">
                    Pembayaran
                </a>

                <a href="#pengiriman"
                    class="block rounded-lg px-3 py-2.5 text-gray-600 hover:bg-gray-100">
                    Pengiriman & Resi
                </a>

                <a href="#custom-design"
                    class="block rounded-lg px-3 py-2.5 text-gray-600 hover:bg-gray-100">
                    Custom Design
                </a>

                <a href="#promosi"
                    class="block rounded-lg px-3 py-2.5 text-gray-600 hover:bg-gray-100">
                    Promosi Produk
                </a>

                <a href="#laporan"
                    class="block rounded-lg px-3 py-2.5 text-gray-600 hover:bg-gray-100">
                    Laporan Penjualan
                </a>
            </nav>

            <div class="border-t border-gray-200 p-4">
                <p class="truncate px-3 text-sm font-medium">
                    {{ auth()->user()->name }}
                </p>
                <p class="px-3 text-xs text-gray-500">Administrator</p>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-lg px-3 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Konten utama -->
        <main class="min-w-0 flex-1 p-5 md:p-8">
            <header class="mb-8 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold">
                        @yield('heading', 'Dashboard')
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Kelola operasional toko Meooni.id.
                    </p>
                </div>

                <span class="rounded-full bg-pink-50 px-4 py-2 text-sm font-medium text-pink-700">
                    Admin
                </span>
            </header>

            @yield('content')
        </main>

    </div>
</body>

</html>