@extends('layouts.admin')

@section('title', 'Kategori Produk')
@section('heading', 'Kategori Produk')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-lg font-semibold">Daftar Kategori</h2>
        <p class="mt-1 text-sm text-gray-500">
            Kelola kategori produk Meooni.id.
        </p>
    </div>

    <a href="{{ route('admin.categories.create') }}"
        class="inline-flex items-center justify-center rounded-lg bg-grey-600 px-4 py-2.5 text-sm font-semibold text-black shadow-sm hover:bg-pink-700">
        + Tambah Kategori
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

@if ($errors->any())
<div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
    Periksa kembali data yang dimasukkan.
</div>
@endif

<div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-5 py-4 font-semibold">Nama Kategori</th>
                    <th class="px-5 py-4 font-semibold">Slug</th>
                    <th class="px-5 py-4 font-semibold">Jumlah Produk</th>
                    <th class="px-5 py-4 font-semibold">Status</th>
                    <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($categories as $category)
                <tr>
                    <td class="px-5 py-4 font-medium text-gray-800">
                        {{ $category->name }}
                    </td>

                    <td class="px-5 py-4 text-gray-500">
                        {{ $category->slug }}
                    </td>

                    <td class="px-5 py-4">
                        {{ $category->products_count }}
                    </td>

                    <td class="px-5 py-4">
                        @if ($category->is_active)
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
                            <a href="{{ route('admin.categories.edit', $category) }}"
                                class="font-medium text-pink-600 hover:text-pink-800">
                                Edit
                            </a>

                            <form method="POST"
                                action="{{ route('admin.categories.destroy', $category) }}"
                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="font-medium text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                        Belum ada kategori. Klik Tambah Kategori untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-gray-100 p-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection