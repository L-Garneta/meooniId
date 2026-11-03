@extends('layouts.admin')

@section('content')
<div class="p-6">

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Sampah Produk
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Daftar produk yang telah dinonaktifkan.
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}"
            class="rounded-lg border border-gray-300 bg-white
            px-4 py-2 text-sm font-semibold text-gray-700
            hover:bg-gray-100">
            Kembali ke Produk
        </a>
    </div>

    @if (session('success'))
    <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">
                        Produk
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">
                        Kategori
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">
                        Harga
                    </th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">
                        Tanggal Dihapus
                    </th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($products as $product)
                <tr>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @php
                            $image = $product->images
                            ->firstWhere('is_primary', true)
                            ?? $product->images->first();
                            @endphp

                            @if ($image)
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                class="h-12 w-12 rounded-lg object-contain"
                                alt="{{ $product->name }}">
                            @else
                            <div class="flex h-12 w-12 items-center justify-center
                                        rounded-lg bg-gray-100 text-xs text-gray-400">
                                No Image
                            </div>
                            @endif

                            <span class="font-medium text-gray-800">
                                {{ $product->name }}
                            </span>
                        </div>
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $product->category->name ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $product->deleted_at->format('d/m/Y H:i') }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <form action="{{ route('admin.products.restore', $product->id) }}"
                            method="POST">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                onclick="return confirm('Pulihkan produk ini?')"
                                class="rounded-lg bg-green-600 px-3 py-2
                                    text-xs font-semibold text-white
                                    hover:bg-green-700">
                                Pulihkan
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-gray-500">
                        Tidak ada produk di sampah.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

</div>
@endsection