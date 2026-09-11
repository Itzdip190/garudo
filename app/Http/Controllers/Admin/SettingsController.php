<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'marketplace_name' => env('MARKETPLACE_NAME', 'Garudo B2B Marketplace'),
            'currency' => env('MARKETPLACE_CURRENCY', 'INR'),
            'currency_symbol' => env('MARKETPLACE_CURRENCY_SYMBOL', '₹'),
            'default_commission' => env('MARKETPLACE_DEFAULT_COMMISSION', 10),
            'low_stock_threshold' => env('MARKETPLACE_LOW_STOCK_THRESHOLD', 10),
            'otp_expiry' => env('MARKETPLACE_OTP_EXPIRY', 10),
            'support_email' => env('MARKETPLACE_SUPPORT_EMAIL', 'support@garudo.com'),
            'support_phone' => env('MARKETPLACE_SUPPORT_PHONE', '+91 1800 200 8899'),
            'app_env' => env('APP_ENV', 'local'),
            'app_debug' => env('APP_DEBUG', true),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'marketplace_name' => 'required|string|max:100',
            'default_commission' => 'required|numeric|min:0|max:100',
            'low_stock_threshold' => 'required|integer|min:1',
            'support_email' => 'required|email',
            'support_phone' => 'required|string',
        ]);

        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $content = file_get_contents($envPath);
            
            $replacements = [
                'MARKETPLACE_NAME' => '"' . $request->marketplace_name . '"',
                'MARKETPLACE_DEFAULT_COMMISSION' => $request->default_commission,
                'MARKETPLACE_LOW_STOCK_THRESHOLD' => $request->low_stock_threshold,
                'MARKETPLACE_SUPPORT_EMAIL' => $request->support_email,
                'MARKETPLACE_SUPPORT_PHONE' => '"' . $request->support_phone . '"',
            ];

            foreach ($replacements as $key => $value) {
                if (preg_match("/^{$key}=.*/m", $content)) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
                } else {
                    $content .= "\n{$key}={$value}";
                }
            }

            file_put_contents($envPath, $content);
            \Illuminate\Support\Facades\Artisan::call('config:clear');
        }

        return back()->with('success', 'Marketplace platform settings updated successfully.');
    }
}
