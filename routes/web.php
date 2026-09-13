<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'index'])->name('storefront.index');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/produtos/{product:slug}', [StorefrontController::class, 'show'])->name('storefront.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/webhooks/mercado-pago', WebhookController::class)->middleware('throttle:60,1')->name('webhooks.mercado-pago');

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/checkout/{product}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/retorno/{status}', [CheckoutController::class, 'return'])->name('checkout.return');
});

Route::prefix('customer')->name('customer.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/downloads', [CustomerController::class, 'downloads'])->name('downloads');
    Route::get('/downloads/{order}', DownloadController::class)->middleware('signed')->name('download');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('posts', AdminPostController::class)->except(['show']);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
});

require __DIR__.'/auth.php';
