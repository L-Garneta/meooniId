<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('categories', CategoryController::class)
            ->except(['show']);

        Route::get('/products/trash', [ProductController::class, 'trash'])
            ->name('products.trash');

        Route::patch('/products/{id}/restore', [ProductController::class, 'restore'])
            ->name('products.restore');

        // Halaman Sampah Produk
        Route::get('/products/trash', [ProductController::class, 'trash'])
            ->name('products.trash');

        // Pulihkan produk
        Route::patch('/products/{id}/restore', [ProductController::class, 'restore'])
            ->name('products.restore');

        // Hapus permanen satu produk dari halaman Produk
        Route::delete('/products/{product}/force-delete', [ProductController::class, 'forceDelete'])
            ->name('products.forceDelete');

        // Bersihkan seluruh sampah
        Route::delete('/products/trash/empty', [ProductController::class, 'emptyTrash'])
            ->name('products.emptyTrash');

        Route::resource('products', ProductController::class)
            ->except(['show']);
    });

require __DIR__ . '/auth.php';
