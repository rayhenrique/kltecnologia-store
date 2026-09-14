<?php

use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NewsletterSubscriberController as AdminNewsletterSubscriberController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponValidationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'index'])->name('storefront.index');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/favoritos/items', [FavoriteController::class, 'items'])->name('favorites.items');
Route::post('/cupons/validar', [CouponValidationController::class, 'validateCoupon'])->name('coupons.validate');
Route::post('/newsletter', [NewsletterSubscriptionController::class, 'store'])->name('newsletter.subscribe');
Route::get('/produtos/{product:slug}', [StorefrontController::class, 'show'])->name('storefront.show');
Route::get('/politica-de-privacidade', [LegalController::class, 'privacy'])->name('privacy.index');
Route::get('/termos-de-uso', [LegalController::class, 'terms'])->name('terms.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/webhooks/mercado-pago', WebhookController::class)->middleware('throttle:60,1')->name('webhooks.mercado-pago');

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/processar', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/retorno/{status}', [CheckoutController::class, 'return'])->name('checkout.return');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/checkout/{product}', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::prefix('customer')->name('customer.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/downloads', [CustomerController::class, 'downloads'])->name('downloads');
    Route::get('/downloads/{order}', DownloadController::class)->middleware('signed')->name('download');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('coupons', AdminCouponController::class)->except(['show']);
    Route::resource('posts', AdminPostController::class)->except(['show']);
    Route::resource('blog-categories', AdminBlogCategoryController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class);
    Route::get('/newsletter/export', [AdminNewsletterSubscriberController::class, 'export'])->name('newsletter.export');
    Route::get('/newsletter', [AdminNewsletterSubscriberController::class, 'index'])->name('newsletter.index');
    Route::delete('/newsletter/{subscriber}', [AdminNewsletterSubscriberController::class, 'destroy'])->name('newsletter.destroy');
});

require __DIR__.'/auth.php';
