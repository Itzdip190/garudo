<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
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

        $launchSettings = [
            'launch_mode_active'     => HomepageSetting::get('launch_mode_active', '1'),
            'launch_date'            => HomepageSetting::get('launch_date', '20th October'),
            'launch_strip_text'      => HomepageSetting::get('launch_strip_text', 'Official Launching on 20th October | Pre-Launch Preview'),
            'launch_notice_message'  => HomepageSetting::get('launch_notice_message', 'Official Launching on 20th October. Direct order purchasing will start on our official launch day!'),
            'header_tagline'         => HomepageSetting::get('header_tagline', "India's Leading B2B Commerce & Wholesale Network"),
            'header_tagline_mobile'  => HomepageSetting::get('header_tagline_mobile', "India's #1 B2B Commerce & Wholesale Network"),
        ];

        return view('admin.settings.index', compact('settings', 'launchSettings'));
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

        // Save Launch & Tagline Settings if present
        if ($request->has('has_launch_settings')) {
            HomepageSetting::set('launch_mode_active', $request->has('launch_mode_active') ? '1' : '0');
            if ($request->filled('launch_date')) {
                HomepageSetting::set('launch_date', $request->input('launch_date'));
            }
            if ($request->filled('launch_strip_text')) {
                HomepageSetting::set('launch_strip_text', $request->input('launch_strip_text'));
            }
            if ($request->filled('launch_notice_message')) {
                HomepageSetting::set('launch_notice_message', $request->input('launch_notice_message'));
            }
            if ($request->filled('header_tagline')) {
                HomepageSetting::set('header_tagline', $request->input('header_tagline'));
            }
            if ($request->filled('header_tagline_mobile')) {
                HomepageSetting::set('header_tagline_mobile', $request->input('header_tagline_mobile'));
            }
        }

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
