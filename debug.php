<?php

/**
 * Diagnostic tool to check PHP version, extensions, and exact error log on Hostinger
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<div style='font-family: system-ui, sans-serif; padding: 30px; background: #0f172a; color: #fff; border-radius: 12px; max-width: 800px; margin: 40px auto;'>";
echo "<h2 style='color: #38bdf8;'>🔍 Hostinger PHP Environment Diagnostic</h2>";

// PHP Version
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
if (version_compare(phpversion(), '8.2.0', '<')) {
    echo "<p style='color: #ef4444;'>❌ Laravel 12 requires PHP 8.2 or 8.3. Your Hostinger PHP version is too old!</p>";
} else {
    echo "<p style='color: #22c55e;'>✅ PHP Version satisfies Laravel 12 requirements.</p>";
}

// File check
$indexExists = file_exists(__DIR__ . '/public/index.php');
echo "<p><strong>public/index.php:</strong> " . ($indexExists ? "✅ Found" : "❌ Missing") . "</p>";

// Laravel Log inspection
$logPath = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logPath)) {
    $logContent = file_get_contents($logPath);
    $lines = explode("\n", $logContent);
    $lastLines = array_slice($lines, -30);
    echo "<h3>Recent Laravel Error Log:</h3>";
    echo "<pre style='background: #1e293b; padding: 15px; border-radius: 8px; color: #fca5a5; overflow-x: auto; white-space: pre-wrap; font-size: 12px;'>" . htmlspecialchars(implode("\n", $lastLines)) . "</pre>";
} else {
    echo "<p>No log file at <code>storage/logs/laravel.log</code> yet.</p>";
}

echo "</div>";
