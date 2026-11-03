@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('heading', 'Edit Produk')

@section('content')

<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800">
            Edit Informasi Produk
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi dan gambar produk Meooni.id.
        </p>
    </div>

    <form
        action="{{ route('admin.products.update', $product->id) }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nama Produk -->
        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Nama Produk *
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $product->name) }}"
                maxlength="150"
                required
                class="w-full rounded-lg border-gray-300 shadow-sm
                       focus:border-indigo-500 focus:ring-indigo-500">

            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kategori -->
        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Kategori *
            </label>

            <select
                name="category_id"
                required
                class="w-full rounded-lg border-gray-300 shadow-sm">
                <option value="">Pilih kategori</option>

                @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    @selected(
                    old('category_id', $product->category_id) == $category->id
                    )
                    >
                    {{ $category->name }}
                    @if (!$category->is_active)
                    (Nonaktif)
                    @endif
                </option>
                @endforeach
            </select>

            @error('category_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Harga -->
        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Harga (Rp) *
            </label>

            <input
                type="number"
                name="price"
                value="{{ old('price', $product->price) }}"
                min="0"
                max="9999999999.99"
                step="0.01"
                required
                class="w-full rounded-lg border-gray-300 shadow-sm">

            @error('price')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Stok -->
        <div class="mb-5">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Stok *
            </label>

            <input
                type="number"
                name="stock"
                value="{{ old('stock', $product->stock) }}"
                min="0"
                required
                class="w-full rounded-lg border-gray-300 shadow-sm">

            @error('stock')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="mb-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Deskripsi Produk
            </label>

            <textarea
                name="description"
                rows="4"
                maxlength="10000"
                class="w-full rounded-lg border-gray-300 shadow-sm"
                placeholder="Jelaskan bahan, ukuran, pilihan desain, atau informasi produk lainnya.">{{ old('description', $product->description) }}</textarea>

            @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Gambar Lama -->
        <div class="mb-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Gambar Produk Saat Ini
            </label>

            <p class="mb-3 text-sm text-gray-500">
                Centang gambar yang ingin dihapus. Gambar yang tidak dicentang
                akan tetap tersimpan.
            </p>

            @if ($product->images->isEmpty())
            <p class="text-sm text-gray-400">
                Produk ini belum memiliki gambar.
            </p>
            @else
            <div class="flex flex-wrap gap-4" id="existing-images">

                @foreach ($product->images as $image)
                <div
                    class="existing-image-item w-36 rounded-lg
                                   border border-gray-200 p-3"
                    data-id="{{ $image->id }}"
                    data-url="{{ asset('storage/' . $image->image_path) }}"
                    data-name="{{ basename($image->image_path) }}"
                    data-primary="{{ $image->is_primary ? '1' : '0' }}">

                    <img
                        src="{{ asset('storage/' . $image->image_path) }}"
                        alt="Gambar produk"
                        class="h-24 w-full rounded-md object-contain
                                       bg-gray-100">

                    @if ($image->is_primary)
                    <span class="mt-2 block text-xs font-semibold
                                             text-green-600">
                        Gambar Utama
                    </span>
                    @endif

                    <label class="mt-2 flex cursor-pointer items-center
                                          gap-2 text-xs text-red-600">
                        <input
                            type="checkbox"
                            name="delete_images[]"
                            value="{{ $image->id }}"
                            class="delete-image-checkbox rounded
                                           border-gray-300 text-red-600">
                        Hapus gambar
                    </label>
                </div>
                @endforeach

            </div>
            @endif

            @error('delete_images')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('delete_images.*')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tambah Gambar Baru -->
        <div class="mb-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Tambahkan Gambar Baru
            </label>

            <p class="mb-3 text-sm text-gray-500">
                Pilih gambar satu per satu. Maksimal 5 gambar secara
                keseluruhan, termasuk gambar yang sudah ada.
                Format JPG, PNG, atau WEBP, maksimal 2 MB per gambar.
            </p>

            <div id="image-inputs" class="space-y-3">

                <div class="image-input-row flex items-center gap-3">
                    <input
                        type="file"
                        name="images[]"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="block w-full text-sm text-gray-600
                               file:mr-3 file:rounded-lg file:border-0
                               file:bg-gray-100 file:px-3 file:py-2
                               file:text-sm file:font-medium">

                    <button
                        type="button"
                        class="remove-image-input hidden rounded-lg
                               border border-red-200 px-3 py-2
                               text-sm text-red-600 hover:bg-red-50">
                        Hapus
                    </button>
                </div>

            </div>

            <button
                type="button"
                id="add-image-button"
                class="mt-3 rounded-lg border border-gray-300
                       px-4 py-2 text-sm font-medium text-gray-700
                       hover:bg-gray-50">
                + Tambah Gambar
            </button>

            <p id="image-count" class="mt-2 text-sm text-gray-500"></p>

            @error('images')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('images.*')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pratinjau -->
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

        </div>

        <!-- Pilihan Gambar Utama -->
        <div class="mb-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Gambar Utama Produk
            </label>

            <select
                name="primary_image"
                id="primary-image"
                class="w-full max-w-sm rounded-lg border-gray-300 shadow-sm">
                <option value="">Gunakan gambar utama saat ini</option>
            </select>

            <p class="mt-1 text-xs text-gray-500">
                Gambar utama yang dipilih akan digunakan sebagai gambar
                utama produk.
                Jika memilih gambar baru, gambar baru pertama akan menjadi
                gambar utama.
            </p>

            @error('primary_image')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div class="mb-8">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Status Produk *
            </label>

            <select
                name="is_active"
                required
                class="w-full rounded-lg border-gray-300 shadow-sm">
                <option
                    value="1"
                    @selected(old('is_active', (int) $product->is_active) == 1)
                    >
                    Aktif
                </option>

                <option
                    value="0"
                    @selected(old('is_active', (int) $product->is_active) == 0)
                    >
                    Nonaktif
                </option>
            </select>

            @error('is_active')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol -->
        <div class="flex flex-wrap justify-end gap-3 border-t pt-5">

            <a
                href="{{ route('admin.products.index') }}"
                class="rounded-lg border border-gray-300 px-5 py-2.5
                       text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>

            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5
                       text-sm font-medium text-black hover:bg-indigo-700">
                Simpan Perubahan
            </button>

        </div>

    </form>
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

        const existingContainer = document.getElementById('existing-images');

        let currentIndex = 0;
        let newImages = [];
        let gallery = [];
        let objectUrls = [];

        function getExistingImages() {
            if (!existingContainer) return [];

            return Array.from(
                    existingContainer.querySelectorAll('.existing-image-item')
                )
                .filter(card => {
                    const checkbox = card.querySelector('.delete-image-checkbox');
                    return !checkbox.checked;
                })
                .map(card => ({
                    id: card.dataset.id,
                    src: card.dataset.url,
                    name: card.dataset.name,
                    type: 'existing',
                    isPrimary: card.dataset.primary === '1'
                }));
        }

        function getNewImages() {
            return Array.from(
                    inputContainer.querySelectorAll('input[type="file"]')
                )
                .filter(input => input.files && input.files.length > 0)
                .map(input => ({
                    file: input.files[0],
                    input: input
                }));
        }

        function getSelectedPrimary() {
            return primarySelect.value;
        }

        function updatePrimaryOptions() {
            const existingImages = getExistingImages();
            const selectedValue = getSelectedPrimary();

            primarySelect.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Gunakan gambar utama saat ini';
            primarySelect.appendChild(defaultOption);

            existingImages.forEach(image => {
                const option = document.createElement('option');

                option.value = `existing:${image.id}`;
                option.textContent = `Gambar lama - ${image.name}`;

                primarySelect.appendChild(option);
            });

            if (newImages.length > 0) {
                const option = document.createElement('option');

                option.value = 'new';
                option.textContent = 'Gambar baru pertama';

                primarySelect.appendChild(option);
            }

            const validValue = Array.from(primarySelect.options)
                .some(option => option.value === selectedValue);

            if (validValue) {
                primarySelect.value = selectedValue;
            } else {
                primarySelect.value = '';
            }
        }

        function updateGallery() {
            objectUrls.forEach(url => URL.revokeObjectURL(url));
            objectUrls = [];

            const existingImages = getExistingImages();

            newImages = getNewImages();

            newImages.forEach(image => {
                image.src = URL.createObjectURL(image.file);
                objectUrls.push(image.src);
                image.name = image.file.name;
                image.type = 'new';
            });

            updatePrimaryOptions();

            const primary = getSelectedPrimary();

            if (primary === 'new' && newImages.length > 0) {
                gallery = [
                    newImages[0],
                    ...existingImages,
                    ...newImages.slice(1)
                ];
            } else if (primary.startsWith('existing:')) {
                const primaryId = primary.replace('existing:', '');

                const selected = existingImages.find(
                    image => image.id === primaryId
                );

                gallery = [
                    ...(selected ? [selected] : []),
                    ...existingImages.filter(image => image.id !== primaryId),
                    ...newImages
                ];
            } else {
                gallery = [
                    ...existingImages.filter(image => image.isPrimary),
                    ...existingImages.filter(image => !image.isPrimary),
                    ...newImages
                ];
            }

            if (currentIndex >= gallery.length) {
                currentIndex = Math.max(0, gallery.length - 1);
            }

            renderGallery();
            updateImageCount();
        }

        function renderGallery() {
            const total = gallery.length;

            previewSection.classList.toggle('hidden', total === 0);

            if (total === 0) {
                previewImage.src = '';
                previewFilename.textContent = '';
                slideNumber.textContent = '0 / 0';

                previousButton.disabled = true;
                nextButton.disabled = true;

                return;
            }

            currentIndex = Math.min(currentIndex, total - 1);

            previewImage.src = gallery[currentIndex].src;
            previewFilename.textContent = gallery[currentIndex].name;

            slideNumber.textContent = `${currentIndex + 1} / ${total}`;

            previousButton.disabled = total <= 1;
            nextButton.disabled = total <= 1;

            previousButton.classList.toggle('opacity-40', total <= 1);
            nextButton.classList.toggle('opacity-40', total <= 1);
        }

        function updateImageCount() {
            const existingCount = getExistingImages().length;
            const newCount = newImages.length;
            const total = existingCount + newCount;

            countLabel.textContent =
                `${total} dari 5 gambar digunakan`;

            const inputRows =
                inputContainer.querySelectorAll('.image-input-row').length;

            addButton.disabled =
                existingCount + inputRows >= 5;

            addButton.classList.toggle('opacity-50', addButton.disabled);
        }

        function validateImage(input) {
            if (!input.files.length) return true;

            const file = input.files[0];

            const validTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!validTypes.includes(file.type)) {
                alert('Format gambar harus JPG, PNG, atau WEBP.');
                input.value = '';
                return false;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran gambar maksimal 2 MB.');
                input.value = '';
                return false;
            }

            const existingCount = getExistingImages().length;
            const selectedNewCount = getNewImages().length;

            if (existingCount + selectedNewCount > 5) {
                alert('Maksimal 5 gambar secara keseluruhan.');

                input.value = '';
                return false;
            }

            return true;
        }

        function createImageInput() {
            const row = document.createElement('div');

            row.className = 'image-input-row flex items-center gap-3';

            const input = document.createElement('input');

            input.type = 'file';
            input.name = 'images[]';
            input.accept = '.jpg,.jpeg,.png,.webp';

            input.className =
                'block w-full text-sm text-gray-600 ' +
                'file:mr-3 file:rounded-lg file:border-0 ' +
                'file:bg-gray-100 file:px-3 file:py-2 ' +
                'file:text-sm file:font-medium';

            const removeButton = document.createElement('button');

            removeButton.type = 'button';
            removeButton.textContent = 'Hapus';

            removeButton.className =
                'remove-image-input rounded-lg border ' +
                'border-red-200 px-3 py-2 text-sm text-red-600 ' +
                'hover:bg-red-50';

            row.appendChild(input);
            row.appendChild(removeButton);

            inputContainer.appendChild(row);
        }

        // Tambah input gambar baru.
        addButton.addEventListener('click', function() {
            const existingCount = getExistingImages().length;
            const inputRows =
                inputContainer.querySelectorAll('.image-input-row').length;

            if (existingCount + inputRows >= 5) {
                alert('Maksimal 5 gambar secara keseluruhan.');
                return;
            }

            createImageInput();
            updateImageCount();
        });

        // Perubahan file dan validasi.
        inputContainer.addEventListener('change', function(event) {
            if (event.target.type !== 'file') return;

            if (!validateImage(event.target)) {
                updateGallery();
                return;
            }

            currentIndex = 0;
            updateGallery();
        });

        // Hapus input gambar baru.
        inputContainer.addEventListener('click', function(event) {
            if (!event.target.matches('.remove-image-input')) return;

            const row = event.target.closest('.image-input-row');

            if (row) {
                row.remove();
                currentIndex = 0;
                updateGallery();
            }
        });

        // Hapus gambar lama melalui checkbox.
        if (existingContainer) {
            existingContainer.addEventListener('change', function(event) {
                if (!event.target.matches('.delete-image-checkbox')) return;

                const selectedPrimary = getSelectedPrimary();

                const selectedCard = event.target.closest('.existing-image-item');

                if (
                    event.target.checked &&
                    selectedPrimary === `existing:${selectedCard.dataset.id}`
                ) {
                    primarySelect.value = '';
                }

                currentIndex = 0;
                updateGallery();
            });
        }

        // Ganti gambar utama.
        primarySelect.addEventListener('change', function() {
            currentIndex = 0;
            updateGallery();
        });

        // Slide sebelumnya.
        previousButton.addEventListener('click', function() {
            if (gallery.length <= 1) return;

            currentIndex =
                (currentIndex - 1 + gallery.length) % gallery.length;

            renderGallery();
        });

        // Slide berikutnya.
        nextButton.addEventListener('click', function() {
            if (gallery.length <= 1) return;

            currentIndex = (currentIndex + 1) % gallery.length;

            renderGallery();
        });

        updateGallery();

        // Jika gambar lama dihapus sehingga tersisa ruang,
        // tombol tambah gambar akan diperbarui.
    });
</script>

@endsection