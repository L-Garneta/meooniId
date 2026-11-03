@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('heading', 'Tambah Produk')

@section('content')
<div class="mx-auto max-w-4xl">
    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
        <h2 class="text-lg font-semibold">Informasi Produk</h2>
        <p class="mt-1 text-sm text-gray-500">
            Lengkapi informasi produk yang akan dijual di Meooni.id.
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

        @if ($categories->isEmpty())
        <div class="mt-6 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800">
            Belum ada kategori aktif. Buat atau aktifkan kategori terlebih dahulu.
            <a href="{{ route('admin.categories.index') }}"
                class="ml-1 font-semibold underline">
                Kelola kategori
            </a>
        </div>
        @else
        <form action="{{ route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="mt-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="mb-2 block text-sm font-medium">
                    Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    maxlength="150"
                    required
                    placeholder="Contoh: Stiker Kucing Lucu"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category_id" class="mb-2 block text-sm font-medium">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select id="category_id"
                    name="category_id"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        @selected((string) old('category_id')===(string) $category->id)>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="price" class="mb-2 block text-sm font-medium">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                        id="price"
                        name="price"
                        value="{{ old('price') }}"
                        min="0"
                        max="9999999999.99"
                        step="0.01"
                        required
                        placeholder="15000"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">
                    @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="mb-2 block text-sm font-medium">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                        id="stock"
                        name="stock"
                        value="{{ old('stock', 0) }}"
                        min="0"
                        step="1"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">
                    @error('stock')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="mb-2 block text-sm font-medium">
                    Deskripsi Produk
                </label>
                <textarea id="description"
                    name="description"
                    rows="5"
                    maxlength="10000"
                    placeholder="Jelaskan bahan, ukuran, pilihan desain, atau informasi produk lainnya."
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar Produk
                </label>

                <p class="text-sm text-gray-500 mb-3">
                    Pilih gambar satu per satu. Maksimal 5 gambar, format JPG, PNG,
                    atau WEBP, dengan ukuran maksimal 2 MB per gambar.
                </p>

                <div id="image-inputs" class="space-y-3">
                    <div class="flex items-center gap-3 image-input-row">
                        <input
                            type="file"
                            name="images[]"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full text-sm text-gray-600
                       file:mr-3 file:rounded-lg file:border-0
                       file:bg-gray-100 file:px-3 file:py-2
                       file:text-sm file:font-medium">
                    </div>
                </div>

                <button
                    type="button"
                    id="add-image-button"
                    class="mt-3 rounded-lg border border-gray-300 px-4 py-2
               text-sm font-medium text-gray-700 hover:bg-gray-50">
                    + Tambah Gambar
                </button>

                <p id="image-count" class="mt-2 text-sm text-gray-500">
                    0 dari 5 gambar dipilih
                </p>

                @error('images')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('images.*')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div id="image-preview-section" class="mb-6 hidden">
                <label class="mb-2 block text-sm font-medium text-gray-700">
                    Pratinjau Gambar
                </label>

                <div class="w-48 max-w-full">
                    <div class="flex h-24 w-full items-center justify-center
                    overflow-hidden rounded-lg border border-gray-300
                    bg-gray-100">
                        <img
                            id="preview-image"
                            src=""
                            alt="Pratinjau gambar produk"
                            class="h-full w-full object-contain">
                    </div>

                    <div class="mt-2 flex items-center justify-between gap-2">
                        <button
                            type="button"
                            id="previous-image"
                            class="rounded-lg border border-gray-300 bg-white
                       px-2 py-2 text-xs font-semibold text-gray-800
                       shadow hover:bg-gray-100">
                            &#10094; Sebelumnya
                        </button>

                        <span id="slide-number"
                            class="whitespace-nowrap text-xs text-gray-600">
                            1 / 1
                        </span>

                        <button
                            type="button"
                            id="next-image"
                            class="rounded-lg border border-gray-300 bg-white
                       px-2 py-2 text-xs font-semibold text-gray-800
                       shadow hover:bg-gray-100">
                            Berikutnya &#10095;
                        </button>
                    </div>

                    <p id="preview-filename"
                        class="mt-2 truncate text-xs text-gray-500"></p>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Gambar Utama Produk
                    </label>

                    <select
                        name="primary_image"
                        id="primary-image"
                        class="mt-1 w-full max-w-sm rounded-lg border-gray-300 shadow-sm">
                        <option value="0">Pilih gambar terlebih dahulu</option>
                    </select>

                    <p class="mt-1 text-xs text-gray-500">
                        Gambar utama otomatis dipindahkan ke urutan pertama.
                    </p>

                    @error('primary_image')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const inputContainer = document.getElementById('image-inputs');
                    const addButton = document.getElementById('add-image-button');
                    const countLabel = document.getElementById('image-count');
                    const previewSection = document.getElementById('image-preview-section');
                    const previewImage = document.getElementById('preview-image');
                    const previewFilename = document.getElementById('preview-filename');
                    const slideNumber = document.getElementById('slide-number');
                    const previousButton = document.getElementById('previous-image');
                    const nextButton = document.getElementById('next-image');
                    const primarySelect = document.getElementById('primary-image');

                    let selectedImages = [];
                    let currentIndex = 0;
                    let objectUrls = [];

                    function collectImages() {
                        objectUrls.forEach(url => URL.revokeObjectURL(url));
                        objectUrls = [];

                        selectedImages = [];

                        inputContainer.querySelectorAll('input[type="file"]').forEach(input => {
                            const file = input.files[0];

                            if (file) {
                                selectedImages.push(file);
                            }
                        });

                        selectedImages.forEach(file => {
                            objectUrls.push(URL.createObjectURL(file));
                        });

                        if (currentIndex >= selectedImages.length) {
                            currentIndex = Math.max(0, selectedImages.length - 1);
                        }

                        renderPreview();
                    }

                    function renderPreview() {
                        countLabel.textContent =
                            `${selectedImages.length} dari 5 gambar dipilih`;

                        addButton.disabled = selectedImages.length >= 5;
                        addButton.classList.toggle('opacity-50', addButton.disabled);
                        addButton.classList.toggle('cursor-not-allowed', addButton.disabled);

                        previewSection.classList.toggle(
                            'hidden',
                            selectedImages.length === 0
                        );

                        primarySelect.innerHTML = '';

                        selectedImages.forEach((file, index) => {
                            const option = document.createElement('option');
                            option.value = index;
                            option.textContent = `Gambar ${index + 1} — ${file.name}`;
                            primarySelect.appendChild(option);
                        });

                        if (selectedImages.length === 0) {
                            const option = document.createElement('option');
                            option.value = '0';
                            option.textContent = 'Pilih gambar terlebih dahulu';
                            primarySelect.appendChild(option);
                            return;
                        }

                        currentIndex = Math.min(currentIndex, selectedImages.length - 1);

                        previewImage.src = objectUrls[currentIndex];
                        previewFilename.textContent = selectedImages[currentIndex].name;
                        slideNumber.textContent =
                            `${currentIndex + 1} / ${selectedImages.length}`;

                        previousButton.disabled = selectedImages.length <= 1;
                        nextButton.disabled = selectedImages.length <= 1;

                        previousButton.classList.toggle(
                            'opacity-40',
                            selectedImages.length <= 1
                        );
                        nextButton.classList.toggle(
                            'opacity-40',
                            selectedImages.length <= 1
                        );
                    }

                    addButton.addEventListener('click', function() {
                        if (selectedImages.length >= 5) return;

                        const row = document.createElement('div');
                        row.className = 'flex items-center gap-3 image-input-row';

                        const input = document.createElement('input');
                        input.type = 'file';
                        input.name = 'images[]';
                        input.accept = '.jpg,.jpeg,.png,.webp';
                        input.className = 'block w-full text-sm text-gray-600 ' +
                            'file:mr-3 file:rounded-lg file:border-0 ' +
                            'file:bg-gray-100 file:px-3 file:py-2 ' +
                            'file:text-sm file:font-medium';

                        input.addEventListener('change', function() {
                            if (input.files.length > 0) {
                                const file = input.files[0];
                                const validType = [
                                    'image/jpeg',
                                    'image/png',
                                    'image/webp'
                                ].includes(file.type);

                                if (!validType || file.size > 2 * 1024 * 1024) {
                                    alert('Pilih gambar JPG, PNG, atau WEBP maksimal 2 MB.');
                                    input.value = '';
                                }
                            }

                            collectImages();
                        });

                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.textContent = 'Hapus';
                        removeButton.className =
                            'rounded-lg border border-red-200 px-3 py-2 ' +
                            'text-sm text-red-600 hover:bg-red-50';

                        removeButton.addEventListener('click', function() {
                            row.remove();
                            collectImages();
                        });

                        row.appendChild(input);
                        row.appendChild(removeButton);
                        inputContainer.appendChild(row);
                    });

                    inputContainer.querySelectorAll('input[type="file"]').forEach(input => {
                        input.addEventListener('change', function() {
                            if (input.files.length > 0) {
                                const file = input.files[0];
                                const validType = [
                                    'image/jpeg',
                                    'image/png',
                                    'image/webp'
                                ].includes(file.type);

                                if (!validType || file.size > 2 * 1024 * 1024) {
                                    alert('Pilih gambar JPG, PNG, atau WEBP maksimal 2 MB.');
                                    input.value = '';
                                }
                            }

                            collectImages();
                        });
                    });

                    previousButton.addEventListener('click', function() {
                        if (selectedImages.length <= 1) return;

                        currentIndex =
                            (currentIndex - 1 + selectedImages.length) % selectedImages.length;

                        renderPreview();
                    });

                    nextButton.addEventListener('click', function() {
                        if (selectedImages.length <= 1) return;
                        currentIndex = (currentIndex + 1) % selectedImages.length;

                        renderPreview();
                    });



                    primarySelect.addEventListener('change', function() {
                        const primaryIndex = Number(primarySelect.value);
                        const filledInputs = Array.from(
                            inputContainer.querySelectorAll('input[type="file"]')
                        ).filter(input => input.files && input.files.length > 0);

                        if (
                            !Number.isInteger(primaryIndex) ||
                            primaryIndex < 0 ||
                            primaryIndex >= filledInputs.length
                        ) {
                            return;
                        }

                        // Ambil baris input gambar yang dipilih.
                        const selectedRow =
                            filledInputs[primaryIndex].closest('.image-input-row');

                        const firstFilledRow =
                            filledInputs[0].closest('.image-input-row');

                        if (!selectedRow || !firstFilledRow) return;

                        // Pindahkan gambar utama ke urutan input pertama.
                        if (selectedRow !== firstFilledRow) {
                            inputContainer.insertBefore(selectedRow, firstFilledRow);
                        }

                        // Bangun ulang daftar gambar berdasarkan urutan input terbaru.
                        currentIndex = 0;
                        collectImages();

                        // Pastikan pilihan gambar utama menjadi indeks pertama.
                        primarySelect.value = '0';
                    });

                    renderPreview();
                });
            </script>


            <div>
                <label for="is_active" class="mb-2 block text-sm font-medium">
                    Status Produk
                </label>
                <select id="is_active"
                    name="is_active"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-pink-500 focus:outline-none focus:ring-2 focus:ring-pink-100">
                    <option value="1" @selected(old('is_active', '1' )=='1' )>Aktif</option>
                    <option value="0" @selected(old('is_active')=='0' )>Nonaktif</option>
                </select>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">
                <a href="{{ route('admin.products.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-lg bg-pink-600 px-5 py-2.5 text-sm font-semibold text-black hover:bg-pink-700">
                    Simpan Produk
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

@endsection