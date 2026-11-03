@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('heading', 'Edit Kategori')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-lg font-semibold">Edit Informasi Kategori</h2>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi kategori yang dipilih.
        </p>

        @if ($errors->any())
        <div class="mt-5 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold">Ada data yang perlu diperbaiki:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.categories.update', $category) }}"
            method="POST"
            class="mt-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="mb-2 block text-sm font-medium">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    required
                    maxlength="255"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-medium">
                    Deskripsi
                </label>
                <textarea id="description"
                    name="description"
                    rows="4"
                    maxlength="2000"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">{{ old('description', $category->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_active" class="mb-2 block text-sm font-medium">
                    Status Kategori
                </label>
                <select id="is_active"
                    name="is_active"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">
                    <option value="1" @selected((string) old('is_active', (int) $category->is_active) === '1')>
                        Aktif
                    </option>
                    <option value="0" @selected((string) old('is_active', (int) $category->is_active) === '0')>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="{{ route('admin.categories.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-pink-600 px-5 py-2.5 text-sm font-semibold text-black hover:bg-pink-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection