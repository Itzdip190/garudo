<?php

/**
 * Direct Migration Script for Hostinger (Zero Route Dependency)
 * Upload this file as `migrate.php` into Hostinger `public_html/` directory.
 * Access via: https://aquamarine-crane-862066.hostingersite.com/migrate.php?secret=GarudoB2bDeploy2026SecretKeySecure!
 */

$secretKey = isset($_GET['secret']) ? $_GET['secret'] : null;
$expectedSecret = 'GarudoB2bDeploy2026SecretKeySecure!';

if ($secretKey !== $expectedSecret) {
    http_response_code(403);
    die("<h1 style='color:red; font-family:sans-serif;'>403 Forbidden: Invalid Secret Key</h1>");
}

// Find vendor/autoload.php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
    $appPath = __DIR__ . '/bootstrap/app.php';
} elseif (file_exists(__DIR__ . '/../garudo_core/vendor/autoload.php')) {
    require __DIR__ . '/../garudo_core/vendor/autoload.php';
    $appPath = __DIR__ . '/../garudo_core/bootstrap/app.php';
} else {
    die("<h1 style='color:red; font-family:sans-serif;'>Error: vendor/autoload.php not found. Did you upload vendor folder?</h1>");
}

$app = require_once $appPath;
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // 1. Run Safe Migration (Never drop tables in production)
    \Illuminate\Support\Facades\Artisan::call('migrate', [
        '--force' => true,
    ]);
    $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

    // 2. Storage Link
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $storageOutput = \Illuminate\Support\Facades\Artisan::output();
    } catch (\Throwable $e) {
        $storageOutput = "Storage Link Notice: " . $e->getMessage();
    }

    // 3. Clear Caches
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');

    echo "
    <div style='font-family: system-ui, sans-serif; padding: 30px; background: #0f172a; color: #fff; border-radius: 12px; max-width: 800px; margin: 40px auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);'>
        <h2 style='color: #22c55e; margin-top: 0;'>✅ Database Migrations & Seeders Completed!</h2>
        <hr style='border-color: #334155; margin: 20px 0;'>
        <h4 style='color: #94a3b8;'>Migration Log:</h4>
        <pre style='background: #1e293b; padding: 15px; border-radius: 8px; color: #38bdf8; overflow-x: auto; white-space: pre-wrap; font-size: 13px;'>" . htmlspecialchars($migrateOutput) . "</pre>
        <h4 style='color: #94a3b8;'>Storage Link Log:</h4>
        <pre style='background: #1e293b; padding: 15px; border-radius: 8px; color: #f97316; font-size: 13px;'>" . htmlspecialchars($storageOutput) . "</pre>
        <div style='background: #450a0a; border-left: 4px solid #ef4444; padding: 15px; margin-top: 20px; border-radius: 4px;'>
            <strong style='color: #fca5a5;'>SECURITY ACTION REQUIRED:</strong> Delete this <code>migrate.php</code> file from your Hostinger <code>public_html/</code> directory after running!
        </div>
    </div>
    ";
} catch (\Throwable $e) {
    echo "
    <div style='font-family: system-ui, sans-serif; padding: 30px; background: #450a0a; color: #fca5a5; border-radius: 12px; max-width: 800px; margin: 40px auto;'>
        <h2 style='margin-top: 0;'>❌ Migration Failed</h2>
        <p><strong>Error Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
        <pre style='background: #7f1d1d; padding: 15px; border-radius: 8px; color: #fff; white-space: pre-wrap; font-size: 12px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
    </div>
    ";
}
