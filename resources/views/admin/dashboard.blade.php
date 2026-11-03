@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('heading', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Total Produk</p>
        <p class="mt-3 text-3xl font-bold">0</p>
        <p class="mt-2 text-xs text-gray-400">Produk terdaftar</p>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Antrian Pesanan</p>
        <p class="mt-3 text-3xl font-bold">0</p>
        <p class="mt-2 text-xs text-gray-400">Pesanan perlu diproses</p>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Permintaan Custom</p>
        <p class="mt-3 text-3xl font-bold">0</p>
        <p class="mt-2 text-xs text-gray-400">Permintaan desain</p>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">Penjualan Bulan Ini</p>
        <p class="mt-3 text-3xl font-bold">Rp0</p>
        <p class="mt-2 text-xs text-gray-400">Ringkasan penjualan</p>
    </div>

</div>

<div class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
    <section class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm xl:col-span-2">
        <h2 class="text-lg font-semibold">Aktivitas Pesanan</h2>
        <p class="mt-2 text-sm text-gray-500">
            Ringkasan pesanan terbaru akan ditampilkan di sini.
        </p>
        <div class="mt-6 rounded-lg border border-dashed border-gray-300 p-8 text-center text-sm text-gray-400">
            Belum ada data pesanan untuk ditampilkan.
        </div>
    </section>

    <section class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold">Akses Cepat</h2>

        <div class="mt-4 space-y-3">
            <a href="#produk"
                class="block rounded-lg bg-pink-50 p-3 text-sm font-medium text-pink-700 hover:bg-pink-100">
                Kelola Produk
            </a>

            <a href="#pesanan"
                class="block rounded-lg bg-gray-50 p-3 text-sm font-medium hover:bg-gray-100">
                Periksa Antrian Pesanan
            </a>

            <a href="#promosi"
                class="block rounded-lg bg-gray-50 p-3 text-sm font-medium hover:bg-gray-100">
                Buat Promosi
            </a>
        </div>
    </section>
</div>
@endsection