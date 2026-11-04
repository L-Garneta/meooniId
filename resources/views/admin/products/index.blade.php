@extends('layouts.admin')

@section('title', 'Produk')
@section('heading', 'Manajemen Produk')

@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-semibold">Daftar Produk</h2>
        <p class="mt-1 text-sm text-gray-500">
            Kelola produk, harga, stok, dan status penjualan.
        </p>
    </div>

    <a href="{{ route('admin.products.create') }}"
        class="inline-flex items-center justify-center rounded-lg bg-pink-600 px-4 py-2.5 text-sm font-semibold text-black shadow-sm hover:bg-pink-700">
        + Tambah Produk
    </a>

    <a href="{{ route('admin.products.trash') }}"
        class="rounded-lg bg-gray-600 px-4 py-2
    text-sm font-semibold text-black hover:bg-gray-700">
        Sampah Produk
    </a>
</div>

@if (session('success'))
<div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
    {{ session('error') }}
</div>
@endif

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-5 py-4 font-semibold">Produk</th>
                    <th class="px-5 py-4 font-semibold">Kategori</th>
                    <th class="px-5 py-4 font-semibold">Harga</th>
                    <th class="px-5 py-4 font-semibold">Stok</th>
                    <th class="px-5 py-4 font-semibold">Status</th>
                    <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($products as $product)
                <tr>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if ($product->images->firstWhere('is_primary', true))
                            <img
                                src="{{ asset('storage/' . $product->images->firstWhere('is_primary', true)->image_path) }}"
                                alt="{{ $product->name }}"
                                class="h-14 w-14 rounded-lg border border-gray-200 object-cover">
                            @else
                            <div class="flex h-14 w-14 items-center justify-center
                                rounded-lg bg-gray-100 text-xs text-gray-400">
                                No image
                            </div>
                            @endif

                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $product->name }}
                                </p>
                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $product->slug }}
                                </p>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-4 text-gray-600">
                        {{ $product->category->name ?? '-' }}
                    </td>

                    <td class="px-5 py-4 font-medium text-gray-800">
                        Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                    </td>

                    <td class="px-5 py-4">
                        {{ $product->stock }}
                    </td>

                    <td class="px-5 py-4">
                        @if ($product->is_active)
                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                            Aktif
                        </span>
                        @else
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                            Nonaktif
                        </span>
                        @endif
                    </td>

                    <td class="px-5 py-4">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="font-medium text-pink-600 hover:text-pink-800">
                                Edit
                            </a>

                            <div class="flex items-center gap-2">

                                {{-- Buang ke Sampah --}}
                                <form action="{{ route('admin.products.destroy', $product) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        onclick="return confirm('Pindahkan produk ini ke sampah?')"
                                        class="rounded-lg bg-yellow-500 px-3 py-2
                                            text-xs font-semibold text-black hover:bg-yellow-600">
                                        Buang ke Sampah
                                    </button>
                                </form>

                                {{-- Hapus Permanen --}}
                                <form action="{{ route('admin.products.forceDelete', $product) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        onclick="return confirm('PERINGATAN! Produk beserta seluruh gambarnya akan dihapus permanen dan tidak dapat dipulihkan. Lanjutkan?')"
                                        class="rounded-lg bg-red-600 px-3 py-2
                                            text-xs font-semibold text-white hover:bg-red-700">
                                        Hapus Permanen
                                    </button>
                                </form>

                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                        Belum ada produk. Klik Tambah Produk untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-gray-100 p-4">
        {{ $products->links() }}
    </div>
</div>
@endsection