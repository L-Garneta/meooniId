<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'images'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'primary_image' => ['nullable', 'integer', 'min:0', 'max:4'],
        ]);

        $category = Category::findOrFail($validated['category_id']);

        if (! $category->is_active) {
            throw ValidationException::withMessages([
                'category_id' => 'Kategori yang dipilih sedang nonaktif.',
            ]);
        }

        $imageFiles = $request->file('images', []);
        $primaryIndex = (int) $request->input('primary_image', 0);

        if (count($imageFiles) > 0 && ! array_key_exists($primaryIndex, $imageFiles)) {
            throw ValidationException::withMessages([
                'primary_image' => 'Pilih salah satu gambar yang diunggah sebagai gambar utama.',
            ]);
        }

        $storedPaths = [];

        try {
            $product = DB::transaction(function () use (
                $validated,
                $imageFiles,
                $primaryIndex,
                &$storedPaths
            ) {
                $product = Product::create([
                    'category_id' => $validated['category_id'],
                    'name' => $validated['name'],
                    'slug' => $this->generateUniqueSlug($validated['name']),
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'],
                    'stock' => $validated['stock'],
                    'is_active' => $validated['is_active'],
                ]);

                foreach ($imageFiles as $index => $image) {
                    $path = $image->store('products', 'public');

                    if (! $path) {
                        throw new \RuntimeException('Gagal menyimpan gambar produk.');
                    }

                    $storedPaths[] = $path;

                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $index === $primaryIndex,
                        'sort_order' => $index,
                    ]);
                }

                return $product;
            });
        } catch (\Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $product->load('images');

        $categories = Category::where('is_active', true)
            ->orWhere('id', $product->category_id)
            ->orderBy('name')
            ->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'primary_image' => ['nullable', 'string', 'max:255'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'distinct'],
        ]);

        $category = Category::findOrFail($validated['category_id']);

        if (! $category->is_active && $category->id !== $product->category_id) {
            throw ValidationException::withMessages([
                'category_id' => 'Kategori baru yang dipilih sedang nonaktif.',
            ]);
        }

        $newImages = $request->file('images', []);
        $deleteIds = collect($validated['delete_images'] ?? [])
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $currentImages = $product->images()->get();

        foreach ($deleteIds as $id) {
            if (! $currentImages->contains('id', $id)) {
                throw ValidationException::withMessages([
                    'delete_images' => 'Data gambar yang dipilih tidak valid.',
                ]);
            }
        }

        $remainingCount = $currentImages->count() - $deleteIds->count();
        $finalCount = $remainingCount + count($newImages);

        if ($finalCount > 5) {
            throw ValidationException::withMessages([
                'images' => 'Maksimal 5 gambar produk. Hapus gambar lama atau kurangi gambar baru.',
            ]);
        }

        $primary = $validated['primary_image'] ?? null;
        $validPrimary = $primary === null
            || $primary === ''
            || $primary === 'new';

        if (! $validPrimary && ! str_starts_with($primary, 'existing:')) {
            throw ValidationException::withMessages([
                'primary_image' => 'Pilihan gambar utama tidak valid.',
            ]);
        }

        if (str_starts_with((string) $primary, 'existing:')) {
            $primaryId = (int) substr($primary, strlen('existing:'));

            if (
                ! $currentImages->contains('id', $primaryId)
                || $deleteIds->contains($primaryId)
            ) {
                throw ValidationException::withMessages([
                    'primary_image' => 'Gambar utama yang dipilih tidak valid.',
                ]);
            }
        }

        if ($finalCount > 0 && ($primary === null || $primary === '')) {
            $survivingPrimary = $currentImages->first(
                fn($image) => $image->is_primary
                    && ! $deleteIds->contains($image->id)
            );

            if (! $survivingPrimary && count($newImages) === 0) {
                throw ValidationException::withMessages([
                    'primary_image' => 'Pilih gambar utama produk.',
                ]);
            }
        }

        $storedPaths = [];
        $deletedPaths = [];

        try {
            DB::transaction(function () use (
                $validated,
                $product,
                $newImages,
                $deleteIds,
                $primary,
                &$storedPaths,
                &$deletedPaths
            ) {
                foreach ($deleteIds as $id) {
                    $image = $product->images()->findOrFail($id);
                    $deletedPaths[] = $image->image_path;
                    $image->delete();
                }

                foreach ($newImages as $index => $image) {
                    $path = $image->store('products', 'public');

                    if (! $path) {
                        throw new \RuntimeException('Gagal menyimpan gambar produk.');
                    }

                    $storedPaths[] = $path;

                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => false,
                        'sort_order' => $product->images()->count() + $index,
                    ]);
                }

                $product->update([
                    'category_id' => $validated['category_id'],
                    'name' => $validated['name'],
                    'slug' => $this->generateUniqueSlug(
                        $validated['name'],
                        $product->id
                    ),
                    'description' => $validated['description'] ?? null,
                    'price' => $validated['price'],
                    'stock' => $validated['stock'],
                    'is_active' => $validated['is_active'],
                ]);

                $images = $product->images()->orderBy('id')->get();

                if ($images->isNotEmpty()) {
                    if ($primary === 'new') {
                        $primaryImage = $images->first(
                            fn($image) => in_array(
                                $image->image_path,
                                $storedPaths,
                                true
                            )
                        );
                    } elseif (is_string($primary) && str_starts_with($primary, 'existing:')) {
                        $primaryId = (int) substr($primary, strlen('existing:'));
                        $primaryImage = $images->firstWhere('id', $primaryId);
                    } else {
                        $primaryImage = $images->first(
                            fn($image) => $image->is_primary
                        ) ?? $images->first();
                    }

                    if (! $primaryImage) {
                        $primaryImage = $images->first();
                    }

                    foreach ($images as $image) {
                        $image->update([
                            'is_primary' => $image->id === $primaryImage->id,
                        ]);
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }

        foreach ($deletedPaths as $path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    // Soft delete
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dipindahkan ke sampah.');
    }

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'produk';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::withTrashed()
            ->where('slug', $slug)
            ->when(
                $ignoreId !== null,
                fn($query) => $query->where('id', '!=', $ignoreId)
            )
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function trash(): View
    {
        $products = Product::onlyTrashed()
            ->with(['category', 'images'])
            ->latest()
            ->paginate(10);

        return view('admin.products.trash', compact('products'));
    }

    //memulihkan 
    public function restore(string $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->restore();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', 'Produk berhasil dipulihkan.');
    }

    public function forceDelete(Product $product): RedirectResponse
    {
        $product->load('images');

        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->forceDelete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus secara permanen.');
    }

    public function emptyTrash(): RedirectResponse
    {
        $products = Product::onlyTrashed()
            ->with('images')
            ->get();

        foreach ($products as $product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $product->forceDelete();
        }

        return redirect()
            ->route('admin.products.trash')
            ->with('success', 'Trash berhasil dibersihkan.');
    }
}
