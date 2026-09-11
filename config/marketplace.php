<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Marketplace Central Configuration
    |--------------------------------------------------------------------------
    | All marketplace-specific configuration values. Never hardcode these
    | values in controllers or views — always use config('marketplace.*').
    */

    'name'             => env('MARKETPLACE_NAME', 'Garudo Marketplace'),
    'currency'         => env('MARKETPLACE_CURRENCY', 'INR'),
    'currency_symbol'  => env('MARKETPLACE_CURRENCY_SYMBOL', '₹'),
    'timezone'         => env('APP_TIMEZONE', 'Asia/Kolkata'),
    'support_email'    => env('MARKETPLACE_SUPPORT_EMAIL', 'support@garudo.com'),
    'support_phone'    => env('MARKETPLACE_SUPPORT_PHONE', '+911234567890'),

    /*
    |--------------------------------------------------------------------------
    | Commission
    |--------------------------------------------------------------------------
    */
    'commission' => [
        'default_rate'    => env('MARKETPLACE_DEFAULT_COMMISSION', 10), // percentage
        'type'            => 'percentage',                              // 'percentage' | 'fixed'
    ],

    /*
    |--------------------------------------------------------------------------
    | Product
    |--------------------------------------------------------------------------
    */
    'product' => [
        'max_images'          => env('MARKETPLACE_MAX_PRODUCT_IMAGES', 10),
        'max_videos'          => 3,
        'max_documents'       => 5,
        'max_bulk_tiers'      => 5,
        'image_max_size_kb'   => 2048,  // 2MB per image
        'video_max_size_mb'   => 50,
        'allowed_doc_types'   => ['pdf', 'doc', 'docx'],
        'thumbnail_width'     => 400,
        'thumbnail_height'    => 400,
        'gallery_width'       => 800,
        'gallery_height'      => 800,
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        'low_stock_threshold'   => env('MARKETPLACE_LOW_STOCK_THRESHOLD', 10),
        'track_by_default'      => true,
        'allow_backorder'       => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Vendor
    |--------------------------------------------------------------------------
    */
    'vendor' => [
        'require_kyc'           => true,
        'require_approval'      => true,
        'max_products'          => 10000,    // per vendor limit (0 = unlimited)
        'trial_products'        => 10,       // products allowed before KYC approved
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP
    |--------------------------------------------------------------------------
    */
    'otp' => [
        'expiry_minutes'  => env('MARKETPLACE_OTP_EXPIRY', 10),
        'length'          => 6,
        'resend_after'    => 60, // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'pagination' => [
        'products'       => 24,
        'orders'         => 20,
        'vendors'        => 20,
        'reviews'        => 10,
        'admin_default'  => 25,
    ],

    /*
    |--------------------------------------------------------------------------
    | GST (India)
    |--------------------------------------------------------------------------
    */
    'gst' => [
        'enabled'   => true,
        'rates'     => [0, 5, 12, 18, 28],  // standard GST slabs
        'default'   => 18,
    ],

    /*
    |--------------------------------------------------------------------------
    | Shipping
    |--------------------------------------------------------------------------
    */
    'shipping' => [
        'default_days_min'  => 3,
        'default_days_max'  => 7,
        'free_above'        => 999,     // free shipping above ₹999
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Routing
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'admin_prefix'    => 'admin',
        'vendor_prefix'   => 'vendor',
        'customer_prefix' => 'account',
    ],

    /*
    |--------------------------------------------------------------------------
    | File Storage Paths (relative to storage/app/public)
    |--------------------------------------------------------------------------
    */
    'storage' => [
        'products'      => 'products',
        'vendors'       => 'vendors',
        'customers'     => 'customers',
        'categories'    => 'categories',
        'brands'        => 'brands',
        'banners'       => 'banners',
        'cms'           => 'cms',
        'kyc'           => 'kyc',
        'reviews'       => 'reviews',
        'documents'     => 'documents',
        'temp'          => 'temp',
    ],

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */
    'search' => [
        'driver'        => 'mysql',     // 'mysql' | 'meilisearch' | 'algolia'
        'min_chars'     => 3,
        'max_results'   => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Homepage Sections Cache TTL (seconds)
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'homepage'          => 1800,    // 30 minutes
        'categories_menu'   => 3600,    // 1 hour
        'brands'            => 3600,
        'banners'           => 900,     // 15 minutes
        'featured_products' => 1800,
    ],

];
