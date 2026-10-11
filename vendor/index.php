<?php

/**
 * Garudo Subdomain Router - vendor.garudo.in
 * Automatically directs visitors to Vendor Login, Registration & Portal
 */

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH);

if (empty($path) || $path === '/' || $path === '/index.php' || $path === '/login') {
    header('Location: https://garudo.in/vendor/login', true, 302);
    exit;
}

if ($path === '/register') {
    header('Location: https://garudo.in/vendor/register', true, 302);
    exit;
}

if ($path === '/dashboard') {
    header('Location: https://garudo.in/vendor/dashboard', true, 302);
    exit;
}

if (str_starts_with($path, '/vendor')) {
    header('Location: https://garudo.in' . $uri, true, 302);
    exit;
}

header('Location: https://garudo.in/vendor' . $uri, true, 302);
exit;
