<?php

use Illuminate\Http\Request;

$host = $_SERVER['HTTP_HOST'] ?? '';

// If someone accessed via main domain (e.g. garudo.in/seller or garudo.in/vendor),
// redirect them smoothly to the official seller subdomain!
if (!str_starts_with($host, 'seller.') && !str_starts_with($host, 'vendor.')) {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $target = 'https://seller.garudo.in';
    if (str_contains($uri, 'register') || str_contains($uri, 'signup')) {
        $target = 'https://seller.garudo.in/register';
    }
    header('Location: ' . $target, true, 301);
    exit;
}

// Fast-path static asset serving for images & favicon on seller subdomain
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
if ($uri && (str_starts_with($uri, '/images/') || $uri === '/favicon.ico')) {
    $staticFile = __DIR__ . $uri;
    if (!file_exists($staticFile)) {
        $staticFile = __DIR__ . '/../public' . $uri;
    }
    if (file_exists($staticFile) && !is_dir($staticFile)) {
        $mime = $uri === '/favicon.ico' ? 'image/x-icon' : (mime_content_type($staticFile) ?: 'image/png');
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($staticFile));
        header('Cache-Control: public, max-age=86400, must-revalidate');
        readfile($staticFile);
        exit;
    }
}

// When accessed directly via seller.garudo.in:
// Bootstrap Laravel core and render the Vendor/Seller portal on the subdomain!
define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
} elseif (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
} else {
    die("Error: vendor/autoload.php not found. Please ensure core files are in public_html.");
}

$app->handleRequest(Request::capture());
