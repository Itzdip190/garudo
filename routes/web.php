<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;

/*
|--------------------------------------------------------------------------
| Subdomain Portal Routing (Registered FIRST to prevent catch-all match)
|--------------------------------------------------------------------------
*/

// Subdomain static image server closure
$serveStaticImage = function ($file) {
    $path = public_path('images/' . $file);
    if (!file_exists($path)) {
        $path = base_path('images/' . $file);
    }
    if (file_exists($path) && !is_dir($path)) {
        $mime = mime_content_type($path) ?: 'image/png';
        return response()->file($path, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=86400, must-revalidate',
        ]);
    }
    abort(404);
};

// Admin Subdomain: admin.garudo.in
Route::domain('admin.garudo.in')->group(function () use ($serveStaticImage) {
    Route::get('/images/{file}', $serveStaticImage)->where('file', '.*');
    Route::get('/favicon.ico', function () {
        $p = public_path('favicon.ico');
        return file_exists($p) ? response()->file($p, ['Content-Type' => 'image/x-icon']) : abort(404);
    });
    Route::get('/', function () {
        return auth('admin')->check()
            ? redirect('/dashboard')
            : app(App\Http\Controllers\Auth\Admin\LoginController::class)->showLoginForm();
    });
    Route::get('/login', [App\Http\Controllers\Auth\Admin\LoginController::class, 'showLoginForm']);
    Route::post('/login', [App\Http\Controllers\Auth\Admin\LoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\Admin\LoginController::class, 'logout']);
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->middleware('admin.auth');
    Route::fallback(function () {
        return auth('admin')->check()
            ? redirect('/dashboard')
            : app(App\Http\Controllers\Auth\Admin\LoginController::class)->showLoginForm();
    });
});

// Customer Subdomain: customer.garudo.in
Route::domain('customer.garudo.in')->group(function () use ($serveStaticImage) {
    Route::get('/images/{file}', $serveStaticImage)->where('file', '.*');
    Route::get('/favicon.ico', function () {
        $p = public_path('favicon.ico');
        return file_exists($p) ? response()->file($p, ['Content-Type' => 'image/x-icon']) : abort(404);
    });
    Route::get('/', function () {
        return auth('customer')->check()
            ? redirect('/dashboard')
            : app(App\Http\Controllers\Auth\Customer\LoginController::class)->showLoginForm();
    });
    Route::get('/login', [App\Http\Controllers\Auth\Customer\LoginController::class, 'showLoginForm']);
    Route::post('/login', [App\Http\Controllers\Auth\Customer\LoginController::class, 'login']);
    Route::get('/register', [App\Http\Controllers\Auth\Customer\RegisterController::class, 'showRegisterForm']);
    Route::get('/signup', [App\Http\Controllers\Auth\Customer\RegisterController::class, 'showRegisterForm']);
    Route::post('/register', [App\Http\Controllers\Auth\Customer\RegisterController::class, 'register']);
    Route::post('/logout', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'logout']);
    Route::get('/dashboard', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'index'])->middleware('auth:customer');
    Route::fallback(function () {
        return auth('customer')->check()
            ? redirect('/dashboard')
            : app(App\Http\Controllers\Auth\Customer\LoginController::class)->showLoginForm();
    });
});

// Seller Subdomains: seller.garudo.in & vendor.garudo.in
foreach (['seller.garudo.in', 'vendor.garudo.in'] as $sellerDomain) {
    Route::domain($sellerDomain)->group(function () use ($serveStaticImage) {
        Route::get('/images/{file}', $serveStaticImage)->where('file', '.*');
        Route::get('/favicon.ico', function () {
            $p = public_path('favicon.ico');
            return file_exists($p) ? response()->file($p, ['Content-Type' => 'image/x-icon']) : abort(404);
        });
        Route::get('/', function () {
            return auth('vendor')->check()
                ? redirect('/dashboard')
                : app(App\Http\Controllers\Auth\Vendor\LoginController::class)->showLoginForm();
        });
        Route::get('/login', [App\Http\Controllers\Auth\Vendor\LoginController::class, 'showLoginForm']);
        Route::post('/login', [App\Http\Controllers\Auth\Vendor\LoginController::class, 'login']);
        Route::get('/register', [App\Http\Controllers\Auth\Vendor\RegisterController::class, 'showRegisterForm']);
        Route::get('/signup', [App\Http\Controllers\Auth\Vendor\RegisterController::class, 'showRegisterForm']);
        Route::post('/register', [App\Http\Controllers\Auth\Vendor\RegisterController::class, 'register']);
        Route::post('/logout', [App\Http\Controllers\Auth\Vendor\LoginController::class, 'logout']);
        Route::get('/dashboard', [App\Http\Controllers\Vendor\DashboardController::class, 'index'])->middleware('vendor.auth');
        Route::fallback(function () {
            return auth('vendor')->check()
                ? redirect('/dashboard')
                : app(App\Http\Controllers\Auth\Vendor\LoginController::class)->showLoginForm();
        });
    });
}

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/images/{file}', $serveStaticImage)->where('file', '.*');
Route::get('/favicon.ico', function () {
    $p = public_path('favicon.ico');
    return file_exists($p) ? response()->file($p, ['Content-Type' => 'image/x-icon']) : abort(404);
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [FrontendProductController::class, 'show'])->name('products.show');
Route::get('/products', [FrontendProductController::class, 'index'])->name('products.index');
Route::get('/categories', [FrontendProductController::class, 'index'])->name('categories.index');
Route::get('/verify/certificate/{certificate_number}', [\App\Http\Controllers\Frontend\CertificateVerificationController::class, 'verify'])->name('certificate.verify');
Route::get('/verify/dealer-authorization/{certificate_number}', [\App\Http\Controllers\Frontend\DealerAuthorizationVerificationController::class, 'verify'])->name('certificate.dealer.verify');

// Public Vendor Storefront & Reviews
Route::get('/store/{slug}', [\App\Http\Controllers\Frontend\VendorStoreController::class, 'show'])->name('store.show');
Route::post('/store/{slug}/reviews', [\App\Http\Controllers\Frontend\VendorStoreController::class, 'storeReview'])->name('store.reviews.store');

// Platform & Software Feedback / Reviews
Route::get('/feedback', [\App\Http\Controllers\Frontend\FeedbackController::class, 'index'])->name('feedback.index');
Route::post('/feedback', [\App\Http\Controllers\Frontend\FeedbackController::class, 'store'])->name('feedback.store');

// Brands Directory & Products by Brand
Route::get('/brands', [\App\Http\Controllers\Frontend\BrandController::class, 'index'])->name('brands.index');
Route::get('/brand/{slug}', [\App\Http\Controllers\Frontend\BrandController::class, 'show'])->name('brands.show');

// Shopping Cart & Reorder
Route::get('/cart', [\App\Http\Controllers\Frontend\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\Frontend\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\Frontend\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{key}', [\App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [\App\Http\Controllers\Frontend\CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/reorder/{orderId}', [\App\Http\Controllers\Frontend\CartController::class, 'reorder'])->name('cart.reorder');

// Wishlist
Route::get('/wishlist', [\App\Http\Controllers\Frontend\WishlistController::class, 'index'])->name('wishlist.index');
Route::match(['GET', 'POST'], '/wishlist/toggle/{productId}', [\App\Http\Controllers\Frontend\WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::match(['GET', 'POST', 'DELETE'], '/wishlist/remove/{productId}', [\App\Http\Controllers\Frontend\WishlistController::class, 'remove'])->name('wishlist.remove');
Route::match(['GET', 'POST'], '/wishlist/move-to-cart/{productId}', [\App\Http\Controllers\Frontend\WishlistController::class, 'moveToCart'])->name('wishlist.move_to_cart');

// Material Samples & Specimen Requests
Route::post('/sample/request', [\App\Http\Controllers\Frontend\FrontendSampleController::class, 'store'])->name('sample.request.store');

// Ask for Quotation (RFQ) Submissions
Route::post('/quotation/request', [\App\Http\Controllers\Frontend\FrontendQuotationController::class, 'store'])->name('quotation.request.store');

/*
|--------------------------------------------------------------------------
| Secure Deployment Routes for Migration & Seeding (Protected by Secret Key)
|--------------------------------------------------------------------------
*/
Route::get('/run-deploy-migrate-seed', function () {
    $secretKey = request()->query('secret');
    $expectedSecret = env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!');

    if (!$secretKey || $secretKey !== $expectedSecret) {
        abort(403, 'Unauthorized Access: Invalid or Missing Migration Secret Key.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $storageOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        $adminEmail = env('SUPER_ADMIN_EMAIL', 'admin@garudo.com');
        $adminPassword = env('SUPER_ADMIN_PASSWORD', 'Garudo@Admin2026#Secure!');

        return "
            <div style='font-family: sans-serif; padding: 30px; background: #0f172a; color: #fff; border-radius: 8px; max-width: 800px; margin: 50px auto; line-height: 1.6;'>
                <h2 style='color: #22c55e;'>✅ Fresh Migration & Seeding Successful!</h2>
                <hr style='border-color: #334155;'>
                <div style='background: #1e293b; padding: 15px; border-radius: 6px; margin: 15px 0;'>
                    <h3 style='margin-top:0; color: #38bdf8;'>🔐 Super Admin Credentials</h3>
                    <p style='margin: 5px 0;'><strong>Login URL:</strong> <a href='/admin/login' style='color: #60a5fa;' target='_blank'>/admin/login</a></p>
                    <p style='margin: 5px 0;'><strong>Email:</strong> <code style='background: #334155; padding: 2px 6px; border-radius: 4px;'>" . e($adminEmail) . "</code></p>
                    <p style='margin: 5px 0;'><strong>Password:</strong> <code style='background: #334155; padding: 2px 6px; border-radius: 4px;'>" . e($adminPassword) . "</code></p>
                </div>
                <h4>Migration & Seed Output:</h4>
                <pre style='background: #1e293b; padding: 15px; border-radius: 5px; color: #38bdf8; overflow-x: auto;'>" . e($migrateOutput) . "</pre>
                <h4>Storage Link Output:</h4>
                <pre style='background: #1e293b; padding: 15px; border-radius: 5px; color: #f97316;'>" . e($storageOutput) . "</pre>
                <p style='color: #ef4444; font-weight: bold;'>⚠️ SECURITY REMINDER: Protect your secret key or disable these deployment routes when done!</p>
            </div>
        ";
    } catch (\Throwable $e) {
        return response("
            <div style='font-family: sans-serif; padding: 30px; background: #450a0a; color: #fca5a5; border-radius: 8px; max-width: 800px; margin: 50px auto;'>
                <h2>❌ Migration Failed</h2>
                <p><strong>Error:</strong> " . e($e->getMessage()) . "</p>
            </div>
        ", 500);
    }
});

Route::get('/run-migrate', function () {
    $secretKey = request()->query('secret');
    $expectedSecret = env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!');

    if (!$secretKey || $secretKey !== $expectedSecret) {
        abort(403, 'Unauthorized Access: Invalid or Missing Secret Key.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--force' => true,
        ]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('storage:link');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        return "
            <div style='font-family: sans-serif; padding: 30px; background: #0f172a; color: #fff; border-radius: 8px; max-width: 800px; margin: 50px auto; line-height: 1.6;'>
                <h2 style='color: #22c55e;'>✅ Pending Database Migrations Executed!</h2>
                <hr style='border-color: #334155;'>
                <h4>Migration Output:</h4>
                <pre style='background: #1e293b; padding: 15px; border-radius: 5px; color: #38bdf8; overflow-x: auto;'>" . e($migrateOutput) . "</pre>
                <p><a href='/admin/dashboard' style='color: #38bdf8; font-weight: bold;'>Go to Super Admin Command Center →</a></p>
            </div>
        ";
    } catch (\Throwable $e) {
        return response("
            <div style='font-family: sans-serif; padding: 30px; background: #450a0a; color: #fca5a5; border-radius: 8px; max-width: 800px; margin: 50px auto;'>
                <h2>❌ Migration Failed</h2>
                <p><strong>Error:</strong> " . e($e->getMessage()) . "</p>
            </div>
        ", 500);
    }
});

Route::get('/run-seed', function () {
    $secretKey = request()->query('secret');
    $expectedSecret = env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!');

    if (!$secretKey || $secretKey !== $expectedSecret) {
        abort(403, 'Unauthorized Access: Invalid or Missing Secret Key.');
    }

    try {
        // Run pending migrations first to ensure all tables exist safely
        \Illuminate\Support\Facades\Artisan::call('migrate', [
            '--force' => true,
        ]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--force' => true,
        ]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        $adminEmail = env('SUPER_ADMIN_EMAIL', 'admin@garudo.com');
        $adminPassword = env('SUPER_ADMIN_PASSWORD', 'Garudo@Admin2026#Secure!');

        return "
            <div style='font-family: sans-serif; padding: 30px; background: #0f172a; color: #fff; border-radius: 8px; max-width: 800px; margin: 50px auto; line-height: 1.6;'>
                <h2 style='color: #22c55e;'>✅ Database Migrations & Seeding Successful!</h2>
                <hr style='border-color: #334155;'>
                <div style='background: #1e293b; padding: 15px; border-radius: 6px; margin: 15px 0;'>
                    <h3 style='margin-top:0; color: #38bdf8;'>🔐 Super Admin Credentials</h3>
                    <p style='margin: 5px 0;'><strong>Login URL:</strong> <a href='/admin/login' style='color: #60a5fa;' target='_blank'>/admin/login</a></p>
                    <p style='margin: 5px 0;'><strong>Email:</strong> <code style='background: #334155; padding: 2px 6px; border-radius: 4px;'>" . e($adminEmail) . "</code></p>
                    <p style='margin: 5px 0;'><strong>Password:</strong> <code style='background: #334155; padding: 2px 6px; border-radius: 4px;'>" . e($adminPassword) . "</code></p>
                </div>
                <h4>Migration Output:</h4>
                <pre style='background: #1e293b; padding: 15px; border-radius: 5px; color: #38bdf8; overflow-x: auto;'>" . e($migrateOutput) . "</pre>
                <h4>Seed Output:</h4>
                <pre style='background: #1e293b; padding: 15px; border-radius: 5px; color: #a3e635; overflow-x: auto;'>" . e($seedOutput) . "</pre>
                <p><a href='/admin/dashboard' style='color: #38bdf8; font-weight: bold;'>Go to Super Admin Command Center →</a></p>
            </div>
        ";
    } catch (\Throwable $e) {
        return response("
            <div style='font-family: sans-serif; padding: 30px; background: #450a0a; color: #fca5a5; border-radius: 8px; max-width: 800px; margin: 50px auto;'>
                <h2>❌ Seeding Failed</h2>
                <p><strong>Error:</strong> " . e($e->getMessage()) . "</p>
            </div>
        ", 500);
    }
});

/*
|--------------------------------------------------------------------------
| Admin Auth & Control Center Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return auth('admin')->check()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('admin.login');
    });
    Route::get('/login', [App\Http\Controllers\Auth\Admin\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\Admin\LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Auth\Admin\LoginController::class, 'logout'])->name('logout');

    Route::middleware(['admin.auth'])->group(function () {
        // Command Center / Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/launch-mode/toggle', [App\Http\Controllers\Admin\DashboardController::class, 'toggleLaunchMode'])->name('launch-mode.toggle');
        Route::post('/launch-mode/update', [App\Http\Controllers\Admin\DashboardController::class, 'updateLaunchSettings'])->name('launch-mode.update');

        // Vendors / Sellers Management
        Route::get('/vendors', [App\Http\Controllers\Admin\VendorController::class, 'index'])->name('vendors.index');
        Route::get('/vendors/{id}', [App\Http\Controllers\Admin\VendorController::class, 'show'])->name('vendors.show');
        Route::post('/vendors/{id}/approve', [App\Http\Controllers\Admin\VendorController::class, 'approve'])->name('vendors.approve');
        Route::post('/vendors/{id}/reject', [App\Http\Controllers\Admin\VendorController::class, 'reject'])->name('vendors.reject');
        Route::post('/vendors/{id}/toggle-status', [App\Http\Controllers\Admin\VendorController::class, 'toggleStatus'])->name('vendors.toggle_status');
        Route::post('/vendors/{id}/reset-password', [App\Http\Controllers\Admin\VendorController::class, 'resetPassword'])->name('vendors.reset_password');
        
        // Vendor KYC & Compliance Verification Workbench
        Route::get('/vendors/{id}/kyc-review', [App\Http\Controllers\Admin\VendorVerificationController::class, 'review'])->name('vendors.kyc.review');
        Route::post('/vendors/{id}/kyc-approve', [App\Http\Controllers\Admin\VendorVerificationController::class, 'approveKyc'])->name('vendors.kyc.approve');
        Route::post('/vendors/{id}/kyc-reject', [App\Http\Controllers\Admin\VendorVerificationController::class, 'rejectKyc'])->name('vendors.kyc.reject');
        Route::post('/vendors/{id}/kyc-doc-status/{docId}', [App\Http\Controllers\Admin\VendorVerificationController::class, 'updateDocumentStatus'])->name('vendors.kyc.doc_status');

        // Customers / B2B Buyers Management
        Route::get('/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
        Route::post('/customers/{id}/toggle-status', [App\Http\Controllers\Admin\CustomerController::class, 'toggleStatus'])->name('customers.toggle_status');
        Route::post('/customers/{id}/reset-password', [App\Http\Controllers\Admin\CustomerController::class, 'resetPassword'])->name('customers.reset_password');

        // Products Catalog & Moderation
        Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'show'])->name('products.show');
        Route::post('/products/{id}/approve', [App\Http\Controllers\Admin\ProductController::class, 'approve'])->name('products.approve');
        Route::post('/products/{id}/reject', [App\Http\Controllers\Admin\ProductController::class, 'reject'])->name('products.reject');
        Route::post('/products/{id}/toggle-featured', [App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])->name('products.toggle_featured');
        Route::post('/products/{id}/toggle-best-seller', [App\Http\Controllers\Admin\ProductController::class, 'toggleBestSeller'])->name('products.toggle_best_seller');
        Route::post('/products/{id}/toggle-trending', [App\Http\Controllers\Admin\ProductController::class, 'toggleTrending'])->name('products.toggle_trending');
        Route::post('/products/{id}/toggle-new-arrival', [App\Http\Controllers\Admin\ProductController::class, 'toggleNewArrival'])->name('products.toggle_new_arrival');
        Route::post('/products/{id}/toggle-sponsored', [App\Http\Controllers\Admin\ProductController::class, 'toggleSponsored'])->name('products.toggle_sponsored');
        Route::post('/products/{id}/toggle-deal-of-day', [App\Http\Controllers\Admin\ProductController::class, 'toggleDealOfDay'])->name('products.toggle_deal_of_day');
        Route::post('/products/{id}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle_status');
        Route::delete('/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');

        // B2B Orders & Payments Management
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update_status');
        Route::post('/orders/{id}/update-payment', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update_payment');

        // Product Samples & Trial Testing Management
        Route::get('/samples', [App\Http\Controllers\Admin\AdminSampleController::class, 'index'])->name('samples.index');
        Route::get('/samples/vendor-analytics', [App\Http\Controllers\Admin\AdminSampleController::class, 'vendorAnalytics'])->name('samples.vendor-analytics');
        Route::get('/samples/{id}', [App\Http\Controllers\Admin\AdminSampleController::class, 'show'])->name('samples.show');
        Route::post('/samples/{id}/update-status', [App\Http\Controllers\Admin\AdminSampleController::class, 'updateStatus'])->name('samples.update_status');

        // Master Quotations & RFQ Monitor
        Route::get('/quotations', [\App\Http\Controllers\Admin\AdminQuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/vendor-analytics', [\App\Http\Controllers\Admin\AdminQuotationController::class, 'vendorAnalytics'])->name('quotations.vendor-analytics');
        Route::get('/quotations/{id}', [\App\Http\Controllers\Admin\AdminQuotationController::class, 'show'])->name('quotations.show');
        Route::post('/quotations/{id}/update-status', [\App\Http\Controllers\Admin\AdminQuotationController::class, 'updateStatus'])->name('quotations.update_status');

        // Notifications Center
        Route::get('/notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-all-read', [App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.mark_all_read');
        Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.mark_read');

        // Categories & Taxonomy
        Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::post('/categories/sync-hub', [App\Http\Controllers\Admin\CategoryController::class, 'syncHub'])->name('categories.sync-hub');
        Route::post('/categories/update-homepage-order', [App\Http\Controllers\Admin\CategoryController::class, 'updateHomepageOrder'])->name('categories.update-homepage-order');
        Route::post('/categories/{id}/toggle-featured', [App\Http\Controllers\Admin\CategoryController::class, 'toggleFeatured'])->name('categories.toggle-featured');
        Route::put('/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        // Brands Master (Super Admin Brand Hierarchy Management)
        Route::get('/brands', [App\Http\Controllers\Admin\BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [App\Http\Controllers\Admin\BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [App\Http\Controllers\Admin\BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{id}/edit', [App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{id}', [App\Http\Controllers\Admin\BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{id}', [App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('brands.destroy');
        Route::post('/brands/{id}/toggle-status', [App\Http\Controllers\Admin\BrandController::class, 'toggleStatus'])->name('brands.toggle-status');
        Route::post('/brands/{id}/toggle-protection', [App\Http\Controllers\Admin\BrandController::class, 'toggleProtection'])->name('brands.toggle-protection');
        Route::post('/brands/{id}/toggle-featured', [App\Http\Controllers\Admin\BrandController::class, 'toggleFeatured'])->name('brands.toggle-featured');

        // Brand Titles & Custom Tiers (Super Brand, Sub Brand, National Brand, etc.)
        Route::prefix('brand-titles')->name('brand-titles.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BrandTitleController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\BrandTitleController::class, 'store'])->name('store');
            Route::post('/quick-store', [App\Http\Controllers\Admin\BrandTitleController::class, 'quickStore'])->name('quick-store');
            Route::put('/{id}', [App\Http\Controllers\Admin\BrandTitleController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\BrandTitleController::class, 'destroy'])->name('destroy');
        });

        // Brand Registry & Trademark Applications
        Route::get('/brand-registry', [App\Http\Controllers\Admin\AdminBrandRegistryController::class, 'index'])->name('brands.registry.index');
        Route::get('/brand-registry/{id}', [App\Http\Controllers\Admin\AdminBrandRegistryController::class, 'show'])->name('brands.registry.show');
        Route::post('/brand-registry/{id}/approve', [App\Http\Controllers\Admin\AdminBrandRegistryController::class, 'approve'])->name('brands.registry.approve');
        Route::post('/brand-registry/{id}/reject', [App\Http\Controllers\Admin\AdminBrandRegistryController::class, 'reject'])->name('brands.registry.reject');

        // Dealer & Distributor Authorizations
        Route::get('/dealer-authorizations', [App\Http\Controllers\Admin\AdminDealerAuthorizationController::class, 'index'])->name('dealer-authorizations.index');
        Route::get('/dealer-authorizations/{id}', [App\Http\Controllers\Admin\AdminDealerAuthorizationController::class, 'show'])->name('dealer-authorizations.show');
        Route::post('/dealer-authorizations/{id}/approve', [App\Http\Controllers\Admin\AdminDealerAuthorizationController::class, 'approve'])->name('dealer-authorizations.approve');
        Route::post('/dealer-authorizations/{id}/reject', [App\Http\Controllers\Admin\AdminDealerAuthorizationController::class, 'reject'])->name('dealer-authorizations.reject');
        Route::post('/dealer-authorizations/{id}/revoke', [App\Http\Controllers\Admin\AdminDealerAuthorizationController::class, 'revoke'])->name('dealer-authorizations.revoke');

        // Standard Specifications & Attributes
        Route::get('/attributes', [App\Http\Controllers\Admin\AttributeController::class, 'index'])->name('attributes.index');
        Route::post('/attributes', [App\Http\Controllers\Admin\AttributeController::class, 'store'])->name('attributes.store');
        Route::put('/attributes/{id}', [App\Http\Controllers\Admin\AttributeController::class, 'update'])->name('attributes.update');
        Route::delete('/attributes/{id}', [App\Http\Controllers\Admin\AttributeController::class, 'destroy'])->name('attributes.destroy');

        // Vendor Catalog Requests
        Route::get('/catalog-requests', [App\Http\Controllers\Admin\CatalogRequestController::class, 'index'])->name('catalog-requests.index');
        Route::post('/catalog-requests/{id}/approve', [App\Http\Controllers\Admin\CatalogRequestController::class, 'approve'])->name('catalog-requests.approve');
        Route::post('/catalog-requests/{id}/reject', [App\Http\Controllers\Admin\CatalogRequestController::class, 'reject'])->name('catalog-requests.reject');

        // Logistics & Carriers
        Route::get('/logistics', [App\Http\Controllers\Admin\LogisticsController::class, 'index'])->name('logistics.index');
        Route::post('/logistics/{id}/toggle-status', [App\Http\Controllers\Admin\LogisticsController::class, 'toggleStatus'])->name('logistics.toggle_status');
        Route::post('/logistics/{id}/update-rate', [App\Http\Controllers\Admin\LogisticsController::class, 'updateRate'])->name('logistics.update_rate');

        // Homepage Merchandising & Storefront Control
        Route::get('/homepage', [App\Http\Controllers\Admin\HomepageController::class, 'index'])->name('homepage.index');
        Route::post('/homepage/products/toggle', [App\Http\Controllers\Admin\HomepageController::class, 'toggleProductFlag'])->name('homepage.products.toggle');
        Route::post('/homepage/promo-cards', [App\Http\Controllers\Admin\HomepageController::class, 'storePromoCard'])->name('homepage.promo-cards.store');
        Route::post('/homepage/promo-cards/{id}/update', [App\Http\Controllers\Admin\HomepageController::class, 'updatePromoCard'])->name('homepage.promo-cards.update');
        Route::post('/homepage/promo-cards/{id}/toggle', [App\Http\Controllers\Admin\HomepageController::class, 'togglePromoCardStatus'])->name('homepage.promo-cards.toggle');
        Route::delete('/homepage/promo-cards/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'deletePromoCard'])->name('homepage.promo-cards.destroy');
        Route::post('/homepage/trending-settings', [App\Http\Controllers\Admin\HomepageController::class, 'updateTrendingSettings'])->name('homepage.trending-settings.update');
        Route::post('/homepage/banners', [App\Http\Controllers\Admin\HomepageController::class, 'storeBanner'])->name('homepage.banners.store');
        Route::post('/homepage/banners/{id}/update', [App\Http\Controllers\Admin\HomepageController::class, 'updateBanner'])->name('homepage.banners.update');
        Route::post('/homepage/banners/{id}/toggle', [App\Http\Controllers\Admin\HomepageController::class, 'toggleBannerStatus'])->name('homepage.banners.toggle');
        Route::delete('/homepage/banners/{id}', [App\Http\Controllers\Admin\HomepageController::class, 'deleteBanner'])->name('homepage.banners.destroy');
        Route::post('/homepage/hero-cards/update', [App\Http\Controllers\Admin\HomepageController::class, 'updateHeroCards'])->name('homepage.hero-cards.update');
        Route::post('/homepage/new-arrivals/update', [App\Http\Controllers\Admin\HomepageController::class, 'updateNewArrivalsSettings'])->name('homepage.new-arrivals.update');
        Route::post('/homepage/categories/{id}/toggle', [App\Http\Controllers\Admin\HomepageController::class, 'toggleCategoryHomepage'])->name('homepage.categories.toggle');
        Route::post('/homepage/sync-defaults', [App\Http\Controllers\Admin\HomepageController::class, 'syncDefaultProducts'])->name('homepage.sync-defaults');

        // Platform & Marketplace Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

        // Password Reset Requests (Offline / Demo Password Flow)
        Route::get('/password-requests', [App\Http\Controllers\Admin\PasswordRequestController::class, 'index'])->name('password-requests.index');
        Route::post('/password-requests/{id}/approve', [App\Http\Controllers\Admin\PasswordRequestController::class, 'approve'])->name('password-requests.approve');
        Route::post('/password-requests/{id}/reject', [App\Http\Controllers\Admin\PasswordRequestController::class, 'reject'])->name('password-requests.reject');
        Route::delete('/password-requests/{id}', [App\Http\Controllers\Admin\PasswordRequestController::class, 'destroy'])->name('password-requests.destroy');

        // Marketing & Promotional Boost Campaigns Moderation
        Route::get('/marketing/campaigns', [App\Http\Controllers\Admin\AdminMarketingCampaignController::class, 'index'])->name('marketing.campaigns');
        Route::post('/marketing/campaigns/{id}/approve', [App\Http\Controllers\Admin\AdminMarketingCampaignController::class, 'approve'])->name('marketing.campaigns.approve');
        Route::post('/marketing/campaigns/{id}/reject', [App\Http\Controllers\Admin\AdminMarketingCampaignController::class, 'reject'])->name('marketing.campaigns.reject');

        // Subscriptions & Plans Management
        Route::get('/subscriptions/plans', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'plans'])->name('subscriptions.plans');
        Route::post('/subscriptions/plans', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'storePlan'])->name('subscriptions.plans.store');
        Route::put('/subscriptions/plans/{id}', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'updatePlan'])->name('subscriptions.plans.update');
        Route::post('/subscriptions/plans/{id}/toggle-status', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'togglePlanStatus'])->name('subscriptions.plans.toggle_status');
        Route::post('/subscriptions/plans/{id}/toggle', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'togglePlanStatus'])->name('subscriptions.plans.toggle');
        Route::delete('/subscriptions/plans/{id}', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'deletePlan'])->name('subscriptions.plans.destroy');
        Route::delete('/subscriptions/plans/{id}/delete', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'deletePlan'])->name('subscriptions.plans.delete');

        Route::get('/subscriptions', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'subscribers'])->name('subscriptions.index');
        Route::post('/subscriptions/{id}/approve', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'approveSubscription'])->name('subscriptions.approve');
        Route::post('/subscriptions/assign', [App\Http\Controllers\Admin\AdminSubscriptionPlanController::class, 'assignSubscription'])->name('subscriptions.assign');

        // Subadmins & Staff RBAC Access Control
        Route::get('/subadmins', [App\Http\Controllers\Admin\SubadminController::class, 'index'])->name('subadmins.index');
        Route::get('/subadmins/create', [App\Http\Controllers\Admin\SubadminController::class, 'create'])->name('subadmins.create');
        Route::post('/subadmins', [App\Http\Controllers\Admin\SubadminController::class, 'store'])->name('subadmins.store');
        Route::get('/subadmins/{id}/edit', [App\Http\Controllers\Admin\SubadminController::class, 'edit'])->name('subadmins.edit');
        Route::put('/subadmins/{id}', [App\Http\Controllers\Admin\SubadminController::class, 'update'])->name('subadmins.update');
        Route::post('/subadmins/{id}/toggle-status', [App\Http\Controllers\Admin\SubadminController::class, 'toggleStatus'])->name('subadmins.toggle_status');
        Route::post('/subadmins/{id}/reset-password', [App\Http\Controllers\Admin\SubadminController::class, 'resetPassword'])->name('subadmins.reset_password');
        Route::delete('/subadmins/{id}', [App\Http\Controllers\Admin\SubadminController::class, 'destroy'])->name('subadmins.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Vendor Auth & Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\Vendor\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\Vendor\LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [App\Http\Controllers\Auth\Vendor\RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\Vendor\RegisterController::class, 'register'])->name('register.submit');
    Route::post('/logout', [App\Http\Controllers\Auth\Vendor\LoginController::class, 'logout'])->name('logout');

    // Vendor Password Reset Requests (Offline Flow)
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetRequestController::class, 'showVendorRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetRequestController::class, 'storeVendorRequest'])->name('password.request.submit');

    // Force Password Change on First Login with Demo Password
    Route::middleware(['vendor.auth'])->group(function () {
        Route::get('/change-password', [App\Http\Controllers\Auth\Vendor\ForcePasswordChangeController::class, 'showForm'])->name('force_change_password');
        Route::post('/change-password', [App\Http\Controllers\Auth\Vendor\ForcePasswordChangeController::class, 'update'])->name('force_change_password.submit');
    });

    Route::middleware(['vendor.auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');

        // KYC Verification & Document Compliance
        Route::get('/kyc', [App\Http\Controllers\Vendor\KycController::class, 'index'])->name('kyc.index');
        Route::post('/kyc/step', [App\Http\Controllers\Vendor\KycController::class, 'storeStep'])->name('kyc.store_step');
        Route::post('/kyc/submit', [App\Http\Controllers\Vendor\KycController::class, 'submitFinal'])->name('kyc.submit');

        // Verified Channel Partner Digital Certificate
        Route::get('/certificate', [App\Http\Controllers\Vendor\CertificateController::class, 'show'])->name('certificate.show');
        Route::get('/certificate/download', [App\Http\Controllers\Vendor\CertificateController::class, 'download'])->name('certificate.download');

        // Products Management & Actions
        Route::get('/products', [App\Http\Controllers\Vendor\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [App\Http\Controllers\Vendor\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\Vendor\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [App\Http\Controllers\Vendor\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [App\Http\Controllers\Vendor\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [App\Http\Controllers\Vendor\ProductController::class, 'destroy'])->name('products.destroy');

        // Quick Price Change Tool
        Route::get('/products/quick-price', [App\Http\Controllers\Vendor\ProductController::class, 'quickPriceIndex'])->name('products.quick-price');
        Route::post('/products/quick-price/batch', [App\Http\Controllers\Vendor\ProductController::class, 'quickPriceBatchUpdate'])->name('products.quick-price.batch');
        Route::post('/products/quick-price/inline', [App\Http\Controllers\Vendor\ProductController::class, 'quickPriceInlineUpdate'])->name('products.quick-price.inline');

        // Bulk Excel / CSV & Backup Restore
        Route::get('/products/bulk-upload', [App\Http\Controllers\Vendor\ProductController::class, 'bulk'])->name('products.bulk');
        Route::get('/products/export', [App\Http\Controllers\Vendor\ProductController::class, 'export'])->name('products.export');
        Route::get('/products/download-template', [App\Http\Controllers\Vendor\ProductController::class, 'downloadTemplate'])->name('products.template');
        Route::post('/products/import', [App\Http\Controllers\Vendor\ProductController::class, 'import'])->name('products.import');
        Route::get('/products/backup', [App\Http\Controllers\Vendor\ProductController::class, 'backup'])->name('products.backup');
        Route::post('/products/restore', [App\Http\Controllers\Vendor\ProductController::class, 'restore'])->name('products.restore');

        // Manufacturer Multi-Dealer Delivery & F.O.R. Policy Guide
        Route::get('/products/logistics-policy', [App\Http\Controllers\Vendor\ProductController::class, 'logisticsPolicy'])->name('products.logistics-policy');

        // Categories, Brands, Attributes & Catalog Requests
        Route::get('/brands', [App\Http\Controllers\Vendor\ProductController::class, 'brands'])->name('brands');
        Route::post('/brands/quick-store', [App\Http\Controllers\Vendor\ProductController::class, 'ajaxStoreBrand'])->name('brands.quick-store');
        Route::get('/categories', [App\Http\Controllers\Vendor\ProductController::class, 'categories'])->name('categories');
        Route::post('/categories/quick-store', [App\Http\Controllers\Vendor\ProductController::class, 'ajaxStoreCategory'])->name('categories.quick-store');
        Route::get('/attributes', [App\Http\Controllers\Vendor\ProductController::class, 'attributes'])->name('attributes');
        Route::post('/catalog-requests', [App\Http\Controllers\Vendor\ProductController::class, 'storeCatalogRequest'])->name('catalog-requests.store');

        // Brand Registry & OEM Accreditation (Brand Owner)
        Route::get('/brand-registry', [App\Http\Controllers\Vendor\VendorBrandRegistryController::class, 'index'])->name('brand-registry.index');
        Route::get('/brand-registry/create', [App\Http\Controllers\Vendor\VendorBrandRegistryController::class, 'create'])->name('brand-registry.create');
        Route::post('/brand-registry', [App\Http\Controllers\Vendor\VendorBrandRegistryController::class, 'store'])->name('brand-registry.store');
        Route::get('/brand-registry/{id}', [App\Http\Controllers\Vendor\VendorBrandRegistryController::class, 'show'])->name('brand-registry.show');
        Route::get('/brand-registry/{id}/dealers', [App\Http\Controllers\Vendor\VendorBrandRegistryController::class, 'dealers'])->name('brand-registry.dealers');
        Route::post('/brand-registry/{id}/dealers/{authId}/approve', [App\Http\Controllers\Vendor\VendorBrandRegistryController::class, 'approveDealer'])->name('brand-registry.dealers.approve');
        Route::post('/brand-registry/{id}/dealers/{authId}/reject', [App\Http\Controllers\Vendor\VendorBrandRegistryController::class, 'rejectDealer'])->name('brand-registry.dealers.reject');

        // Brand Dealership Authorizations & Certificates (Distributor / Dealer)
        Route::get('/dealer-authorizations', [App\Http\Controllers\Vendor\VendorDealerAuthorizationController::class, 'index'])->name('dealer-authorizations.index');
        Route::get('/dealer-authorizations/apply', [App\Http\Controllers\Vendor\VendorDealerAuthorizationController::class, 'apply'])->name('dealer-authorizations.apply');
        Route::post('/dealer-authorizations', [App\Http\Controllers\Vendor\VendorDealerAuthorizationController::class, 'storeApplication'])->name('dealer-authorizations.store');
        Route::get('/dealer-authorizations/{id}/certificate', [App\Http\Controllers\Vendor\VendorDealerAuthorizationController::class, 'showCertificate'])->name('dealer-authorizations.certificate');

        // Orders & Logistics
        Route::get('/orders', [App\Http\Controllers\Vendor\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [App\Http\Controllers\Vendor\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/production-schedule', [App\Http\Controllers\Vendor\OrderController::class, 'updateProductionLeadTime'])->name('orders.production-schedule');
        Route::post('/orders/{id}/trigger-delivery', [App\Http\Controllers\Vendor\OrderController::class, 'triggerDelivery'])->name('orders.trigger-delivery');
        Route::post('/orders/{id}/confirm-quote', [App\Http\Controllers\Vendor\OrderController::class, 'confirmDeliveryQuote'])->name('orders.confirm-quote');
        Route::post('/orders/{id}/self-handled-delivery', [App\Http\Controllers\Vendor\OrderController::class, 'selfHandledDelivery'])->name('orders.self-handled-delivery');
        Route::post('/orders/{id}/verify-pickup-otp', [App\Http\Controllers\Vendor\OrderController::class, 'verifyPickupOtp'])->name('orders.verify-pickup-otp');
        Route::get('/orders/{id}/print-invoice', [App\Http\Controllers\Vendor\OrderController::class, 'printInvoice'])->name('orders.print-invoice');
        Route::get('/returns', [App\Http\Controllers\Vendor\OrderController::class, 'returns'])->name('returns');
        Route::get('/bulk-orders', [App\Http\Controllers\Vendor\OrderController::class, 'bulkOrders'])->name('bulk-orders');

        // Product Samples & Trial Management
        Route::get('/samples', [App\Http\Controllers\Vendor\VendorSampleController::class, 'index'])->name('samples.index');
        Route::get('/samples/{id}', [App\Http\Controllers\Vendor\VendorSampleController::class, 'show'])->name('samples.show');
        Route::post('/samples/{id}/approve', [App\Http\Controllers\Vendor\VendorSampleController::class, 'approve'])->name('samples.approve');
        Route::post('/samples/{id}/dispatch', [App\Http\Controllers\Vendor\VendorSampleController::class, 'dispatch'])->name('samples.dispatch');
        Route::post('/samples/{id}/deliver', [App\Http\Controllers\Vendor\VendorSampleController::class, 'deliver'])->name('samples.deliver');
        Route::post('/samples/{id}/reject', [App\Http\Controllers\Vendor\VendorSampleController::class, 'reject'])->name('samples.reject');
        Route::post('/samples/{id}/convert-quote', [App\Http\Controllers\Vendor\VendorSampleController::class, 'convertToQuote'])->name('samples.convert-quote');

        // Quotations & RFQ Desk
        Route::get('/quotations', [\App\Http\Controllers\Vendor\QuotationController::class, 'index'])->name('quotations.index');
        Route::get('/quotations/{id}', [\App\Http\Controllers\Vendor\QuotationController::class, 'show'])->name('quotations.show');
        Route::post('/quotations/{id}/send-proposal', [\App\Http\Controllers\Vendor\QuotationController::class, 'sendProposal'])->name('quotations.send_proposal');
        Route::post('/quotations/{id}/reject', [\App\Http\Controllers\Vendor\QuotationController::class, 'reject'])->name('quotations.reject');
        Route::post('/quotations/{id}/convert-to-order', [\App\Http\Controllers\Vendor\QuotationController::class, 'convertToOrder'])->name('quotations.convert');

        // Finance & Payments
        Route::get('/earnings', [App\Http\Controllers\Vendor\FinanceController::class, 'earnings'])->name('earnings');
        Route::get('/payments', [App\Http\Controllers\Vendor\FinanceController::class, 'payments'])->name('payments');

        // Reviews & Ratings
        Route::get('/reviews', [App\Http\Controllers\Vendor\ReviewController::class, 'index'])->name('reviews');

        // Customers
        Route::get('/customers', [App\Http\Controllers\Vendor\CustomerController::class, 'index'])->name('customers');

        // Analytics & Reports
        Route::get('/analytics', [App\Http\Controllers\Vendor\AnalyticsController::class, 'index'])->name('analytics');

        // Messages & Buyer RFQs
        Route::get('/messages', [App\Http\Controllers\Vendor\VendorMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{id}', [App\Http\Controllers\Vendor\VendorMessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/mark-all-read', [App\Http\Controllers\Vendor\VendorMessageController::class, 'markAllRead'])->name('messages.mark_all_read');
        Route::post('/messages/{id}/reply', [App\Http\Controllers\Vendor\VendorMessageController::class, 'reply'])->name('messages.reply');

        // Notifications Center
        Route::get('/notifications', [App\Http\Controllers\Vendor\VendorNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-all-read', [App\Http\Controllers\Vendor\VendorNotificationController::class, 'markAllRead'])->name('notifications.mark_all_read');
        Route::post('/notifications/{id}/mark-read', [App\Http\Controllers\Vendor\VendorNotificationController::class, 'markRead'])->name('notifications.mark_read');

        // Marketing Tools, Coupons & Promotional Boost
        Route::get('/marketing', [App\Http\Controllers\Vendor\MarketingController::class, 'index'])->name('marketing');
        Route::post('/marketing/coupon', [App\Http\Controllers\Vendor\MarketingController::class, 'storeCoupon'])->name('marketing.coupon.store');
        Route::post('/marketing/coupon/{id}/toggle', [App\Http\Controllers\Vendor\MarketingController::class, 'toggleCoupon'])->name('marketing.coupon.toggle');
        Route::delete('/marketing/coupon/{id}', [App\Http\Controllers\Vendor\MarketingController::class, 'deleteCoupon'])->name('marketing.coupon.delete');
        Route::post('/marketing/boost/launch', [App\Http\Controllers\Vendor\MarketingController::class, 'launchBoost'])->name('marketing.boost.launch');

        // Subscription & Membership Plans (24/7 Support, Sponsored Boosts, 0% Commission)
        Route::get('/subscription', [App\Http\Controllers\Vendor\VendorSubscriptionController::class, 'index'])->name('subscription.index');
        Route::post('/subscription/subscribe', [App\Http\Controllers\Vendor\VendorSubscriptionController::class, 'subscribe'])->name('subscription.subscribe');

        // Store Management & Storefront Designer
        Route::get('/store/design', [App\Http\Controllers\Vendor\StoreController::class, 'design'])->name('store.design');
        Route::post('/store/design', [App\Http\Controllers\Vendor\StoreController::class, 'updateDesign'])->name('store.design.update');
        Route::get('/store/profile', [App\Http\Controllers\Vendor\StoreController::class, 'profile'])->name('store.profile');
        Route::get('/store/business', [App\Http\Controllers\Vendor\StoreController::class, 'business'])->name('store.business');
        Route::get('/store/media', [App\Http\Controllers\Vendor\StoreController::class, 'media'])->name('store.media');

        // Shipping Settings & Multi-Warehouse Dispatch
        Route::get('/shipping', [App\Http\Controllers\Vendor\ShippingController::class, 'index'])->name('shipping');
        Route::post('/shipping/update', [App\Http\Controllers\Vendor\ShippingController::class, 'update'])->name('shipping.update');
        Route::get('/warehouses', [App\Http\Controllers\Vendor\WarehouseController::class, 'index'])->name('warehouses.index');
        Route::post('/warehouses', [App\Http\Controllers\Vendor\WarehouseController::class, 'store'])->name('warehouses.store');
        Route::put('/warehouses/{id}', [App\Http\Controllers\Vendor\WarehouseController::class, 'update'])->name('warehouses.update');
        Route::delete('/warehouses/{id}', [App\Http\Controllers\Vendor\WarehouseController::class, 'destroy'])->name('warehouses.destroy');
        Route::post('/warehouses/{id}/set-primary', [App\Http\Controllers\Vendor\WarehouseController::class, 'setPrimary'])->name('warehouses.set-primary');

        // Profile & Business
        Route::get('/profile', [App\Http\Controllers\Vendor\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [App\Http\Controllers\Vendor\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [App\Http\Controllers\Vendor\ProfileController::class, 'updatePassword'])->name('profile.password');

        // Support Tickets
        Route::get('/support', [App\Http\Controllers\Vendor\SupportController::class, 'index'])->name('support');
        Route::post('/support/ticket', [App\Http\Controllers\Vendor\SupportController::class, 'storeTicket'])->name('support.ticket');

        // Settings
        Route::get('/settings', [App\Http\Controllers\Vendor\SettingsController::class, 'index'])->name('settings');
    });
});

/*
|--------------------------------------------------------------------------
| Customer Auth & Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::prefix('account')->name('customer.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\Customer\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\Customer\LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [App\Http\Controllers\Auth\Customer\RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\Customer\RegisterController::class, 'register'])->name('register.submit');
    Route::post('/logout', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'logout'])->name('logout');

    // Customer Password Reset Requests (Offline Flow)
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetRequestController::class, 'showCustomerRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetRequestController::class, 'storeCustomerRequest'])->name('password.request.submit');

    // Force Password Change on First Login with Demo Password
    Route::get('/change-password', [App\Http\Controllers\Auth\Customer\ForcePasswordChangeController::class, 'showForm'])->name('force_change_password');
    Route::post('/change-password', [App\Http\Controllers\Auth\Customer\ForcePasswordChangeController::class, 'update'])->name('force_change_password.submit');

    // Customer Dashboard & Pages
    Route::get('/dashboard', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'orderDetails'])->name('orders.show');
    Route::get('/samples', [App\Http\Controllers\Customer\CustomerSampleController::class, 'index'])->name('samples.index');
    Route::get('/samples/{id}', [App\Http\Controllers\Customer\CustomerSampleController::class, 'show'])->name('samples.show');
    Route::get('/quotes', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'quotes'])->name('quotes');
    Route::post('/quotes/{id}/accept', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'acceptQuote'])->name('quotes.accept');
    Route::post('/quotes/{id}/reject', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'rejectQuote'])->name('quotes.reject');
    Route::get('/wishlist', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'wishlist'])->name('wishlist');
    Route::get('/addresses', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'storeAddress'])->name('addresses.store');
    Route::get('/payment-methods', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'paymentMethods'])->name('payment-methods');
    Route::get('/reviews', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'reviews'])->name('reviews');
    Route::get('/returns', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'returns'])->name('returns');
    Route::get('/inquiries', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'inquiries'])->name('inquiries');
    Route::get('/notifications', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'notifications'])->name('notifications');
    Route::get('/membership', [App\Http\Controllers\Customer\CustomerMembershipController::class, 'index'])->name('membership.index');
    Route::get('/settings', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings/profile', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'updatePassword'])->name('settings.password');
    Route::get('/', function() {
        return redirect()->route('customer.dashboard');
    });
});

// Clean Demo Data Endpoint (Cleans demo orders & products so vendors start from 0 actual live data)
Route::get('/clean-demo-data', function () {
    $secretKey = request()->query('secret');
    $expectedSecret = env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!');

    if (!$secretKey || $secretKey !== $expectedSecret) {
        abort(403, 'Unauthorized Access: Invalid or Missing Secret Key.');
    }

    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('delivery_quotes')) {
            \Illuminate\Support\Facades\DB::table('delivery_quotes')->delete();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('order_shipments')) {
            \Illuminate\Support\Facades\DB::table('order_shipments')->delete();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('order_items')) {
            \Illuminate\Support\Facades\DB::table('order_items')->whereIn('product_sku', ['GRD-STL-892', 'DEMO-STL-1'])->orWhere('product_name', 'LIKE', '%Structural Steel Beams%')->delete();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('orders')) {
            \Illuminate\Support\Facades\DB::table('orders')->whereIn('order_number', ['ORD-2026-89421', 'ORD-2026-77319'])->delete();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
            \Illuminate\Support\Facades\DB::table('products')->where('sku', 'GRD-STL-892')->delete();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('sample_requests')) {
            \Illuminate\Support\Facades\DB::table('sample_requests')->whereIn('request_number', [
                'SMP-2026-10145', 'SMP-2026-29182', 'SMP-2026-44819', 'SMP-2026-73190'
            ])->delete();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('quotations')) {
            \Illuminate\Support\Facades\DB::table('quotations')->whereIn('quotation_number', [
                'RFQ-2026-10491', 'RFQ-2026-28190', 'RFQ-2026-39145', 'RFQ-2026-44021'
            ])->delete();
        }

        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        return "<div style='font-family: sans-serif; padding: 30px; background: #0f172a; color: #fff; border-radius: 8px; max-width: 800px; margin: 50px auto; line-height: 1.6;'>
            <h2 style='color: #22c55e;'>✅ Demo Data Cleaned Successfully!</h2>
            <p>All demo sample requests and orders have been purged. Vendor, customer, and admin dashboards now compute 100% strictly actual live data from zero.</p>
            <p><a href='/vendor/dashboard' style='color: #38bdf8; font-weight: bold;'>Go to Vendor Dashboard →</a></p>
        </div>";
    } catch (\Throwable $e) {
        return response("Error: " . $e->getMessage(), 500);
    }
});

// Secure migration and database helper endpoints for deployment / local updates
Route::get('/run-migrate', function () {
    $secretKey = request()->query('secret');
    $expectedSecret = env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!');

    if (!$secretKey || $secretKey !== $expectedSecret) {
        abort(403, 'Unauthorized Access: Invalid or Missing Secret Key.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        return "<div style='font-family: sans-serif; padding: 30px; background: #0b4d3c; color: #fff; border-radius: 8px; max-width: 800px; margin: 50px auto; line-height: 1.6;'>
            <h2 style='color: #34d399;'>✅ Database Migrations Run Successfully!</h2>
            <pre style='background: #062b22; padding: 15px; border-radius: 6px; overflow-x: auto; color: #a7f3d0;'>" . e($output) . "</pre>
            <p><a href='/admin/samples' style='color: #fbbf24; font-weight: bold;'>Go to Admin Sample Monitor →</a> | <a href='/vendor/samples' style='color: #6ee7b7;'>Vendor Samples →</a></p>
        </div>";
    } catch (\Throwable $e) {
        return response("Migration Error: " . $e->getMessage(), 500);
    }
});

Route::get('/run-seed-samples', function () {
    $secretKey = request()->query('secret');
    $expectedSecret = env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!');

    if (!$secretKey || $secretKey !== $expectedSecret) {
        abort(403, 'Unauthorized Access: Invalid or Missing Secret Key.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\SampleSystemSeeder',
            '--force' => true
        ]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        return "<div style='font-family: sans-serif; padding: 30px; background: #0b4d3c; color: #fff; border-radius: 8px; max-width: 800px; margin: 50px auto; line-height: 1.6;'>
            <h2 style='color: #34d399;'>✅ Sample System Seeded Successfully!</h2>
            <pre style='background: #062b22; padding: 15px; border-radius: 6px; overflow-x: auto; color: #a7f3d0;'>" . e($output) . "</pre>
            <p><a href='/admin/samples' style='color: #fbbf24; font-weight: bold;'>Go to Admin Sample Monitor →</a> | <a href='/admin/samples/vendor-analytics' style='color: #fbbf24; font-weight: bold;'>Vendor Analytics →</a></p>
        </div>";
    } catch (\Throwable $e) {
        return response("Seeding Error: " . $e->getMessage(), 500);
    }
});

Route::get('/run-deploy-samples', function () {
    $secretKey = request()->query('secret');
    $expectedSecret = env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!');

    if (!$secretKey || $secretKey !== $expectedSecret) {
        abort(403, 'Unauthorized Access: Invalid or Missing Secret Key.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\SampleSystemSeeder',
            '--force' => true
        ]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        return "<div style='font-family: sans-serif; padding: 30px; background: #0b4d3c; color: #fff; border-radius: 8px; max-width: 800px; margin: 50px auto; line-height: 1.6;'>
            <h2 style='color: #34d399;'>✅ Sample System Deployed & Seeded Successfully!</h2>
            <h4 style='color: #6ee7b7; margin-bottom: 5px;'>Migration Output:</h4>
            <pre style='background: #062b22; padding: 12px; border-radius: 6px; overflow-x: auto; color: #a7f3d0; margin-bottom: 15px;'>" . e($migrateOutput) . "</pre>
            <h4 style='color: #6ee7b7; margin-bottom: 5px;'>Seeder Output:</h4>
            <pre style='background: #062b22; padding: 12px; border-radius: 6px; overflow-x: auto; color: #a7f3d0;'>" . e($seedOutput) . "</pre>
            <p style='margin-top: 20px;'>
                <a href='/admin/samples' style='background: #10b981; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;'>Go to Admin Sample Monitor →</a>
                <a href='/admin/samples/vendor-analytics' style='background: #f59e0b; color: #000; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;'>Vendor Analytics →</a>
                <a href='/vendor/samples' style='background: #3b82f6; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold;'>Vendor Desk →</a>
            </p>
        </div>";
    } catch (\Throwable $e) {
        return response("Deployment Error: " . $e->getMessage(), 500);
    }
});

Route::get('/run-deploy-quotations', function () {
    $secretKey = request()->query('secret');
    $expectedSecret = env('MIGRATION_SECRET_KEY', 'GarudoB2bDeploy2026SecretKeySecure!');

    if (!$secretKey || $secretKey !== $expectedSecret) {
        abort(403, 'Unauthorized Access: Invalid or Missing Secret Key.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        return "<div style='font-family: sans-serif; padding: 30px; background: #0b4d3c; color: #fff; border-radius: 8px; max-width: 800px; margin: 50px auto; line-height: 1.6;'>
            <h2 style='color: #34d399;'>✅ Quotation System Migrations Deployed Successfully!</h2>
            <h4 style='color: #6ee7b7; margin-bottom: 5px;'>Migration Output:</h4>
            <pre style='background: #062b22; padding: 12px; border-radius: 6px; overflow-x: auto; color: #a7f3d0; margin-bottom: 15px;'>" . e($migrateOutput) . "</pre>
            <p style='margin-top: 20px;'>
                <a href='/admin/quotations' style='background: #10b981; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;'>Admin Quotations Monitor →</a>
                <a href='/admin/quotations/vendor-analytics' style='background: #f59e0b; color: #000; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-right: 10px;'>Vendor RFQ Analytics →</a>
                <a href='/vendor/quotations' style='background: #3b82f6; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold;'>Vendor RFQ Desk →</a>
            </p>
        </div>";
    } catch (\Throwable $e) {
        return response("Deployment Error: " . $e->getMessage(), 500);
    }
});

// Friendly aliases for Customer Dashboard & Auth Shortcuts
Route::redirect('/customer', '/account/dashboard');
Route::redirect('/customer/dashboard', '/account/dashboard');
Route::redirect('/customer-dashboard', '/account/dashboard');
Route::redirect('/login', '/account/login');
Route::redirect('/register', '/account/register');
Route::redirect('/customer/login', '/account/login');
Route::redirect('/customer/register', '/account/register');

// Failsafe Storage Route: Serves uploaded files even if symlink is disabled or missing on shared hosting
Route::get('/storage/{extra}', function ($extra) {
    $path = storage_path('app/public/' . $extra);
    if (!file_exists($path) || !is_file($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('extra', '.*');



