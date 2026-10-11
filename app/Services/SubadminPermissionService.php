<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class SubadminPermissionService
{
    /**
     * Get all structured permission definitions organized by module.
     */
    public static function getModuleDefinitions(): array
    {
        return [
            'Dashboard & Analytics' => [
                'icon' => 'bi-speedometer2',
                'description' => 'Command center overview, statistics and system activity',
                'permissions' => [
                    'dashboard.view' => [
                        'label' => 'View Command Center & KPIs',
                        'desc' => 'Access executive overview, transaction stats and quick analytics',
                    ],
                ],
            ],

            'Vendors / Sellers' => [
                'icon' => 'bi-shop-window',
                'description' => 'Supplier management, onboarding verification and seller status',
                'permissions' => [
                    'vendors.view' => [
                        'label' => 'View Vendors Directory',
                        'desc' => 'Browse registered suppliers, company profiles and bank info',
                    ],
                    'vendors.approve' => [
                        'label' => 'Approve / Reject Vendors',
                        'desc' => 'Authorize new vendor signups or decline unverified suppliers',
                    ],
                    'vendors.edit' => [
                        'label' => 'Edit Vendor Details & Passwords',
                        'desc' => 'Update supplier information and issue emergency password resets',
                    ],
                    'vendors.kyc' => [
                        'label' => 'Verify Business KYC Documents',
                        'desc' => 'Inspect and verify GST, PAN, Aadhaar and company trade certificates',
                    ],
                    'vendors.delete' => [
                        'label' => 'Suspend or Deactivate Vendors',
                        'desc' => 'Block or suspend non-compliant seller storefronts',
                    ],
                ],
            ],

            'Buyers / Customers' => [
                'icon' => 'bi-people-fill',
                'description' => 'B2B commercial buyers, procurement managers and accounts',
                'permissions' => [
                    'customers.view' => [
                        'label' => 'View Customers Directory',
                        'desc' => 'Browse B2B buyer profiles, shipping addresses and order histories',
                    ],
                    'customers.edit' => [
                        'label' => 'Edit Customer & Toggle Status',
                        'desc' => 'Modify buyer contact records and activate/deactivate accounts',
                    ],
                    'customers.delete' => [
                        'label' => 'Delete / Suspend Buyers',
                        'desc' => 'Remove or suspend abusive buyer accounts',
                    ],
                ],
            ],

            'Products & Catalog' => [
                'icon' => 'bi-box-seam-fill',
                'description' => 'Master product catalog, pricing, bulk discounts and moderation',
                'permissions' => [
                    'products.view' => [
                        'label' => 'View Products Catalog',
                        'desc' => 'Browse all listings across vendors, inventory levels and pricing',
                    ],
                    'products.create' => [
                        'label' => 'Create New Products',
                        'desc' => 'Add new products and catalog items directly to the marketplace',
                    ],
                    'products.edit' => [
                        'label' => 'Edit Products & Pricing',
                        'desc' => 'Modify product titles, descriptions, technical specs and pricing tiers',
                    ],
                    'products.approve' => [
                        'label' => 'Moderate & Approve Products',
                        'desc' => 'Review vendor submitted products and approve or reject them',
                    ],
                    'products.delete' => [
                        'label' => 'Delete Products',
                        'desc' => 'Remove products permanently from the public catalog',
                    ],
                    'products.flags' => [
                        'label' => 'Merchandising Flags (Featured, Deals)',
                        'desc' => 'Toggle Featured, Best Seller, Trending, Deals & Sponsored badges',
                    ],
                ],
            ],

            'Categories & Tree' => [
                'icon' => 'bi-diagram-3-fill',
                'description' => 'Marketplace product categorization and hierarchical taxonomy',
                'permissions' => [
                    'categories.view' => [
                        'label' => 'View Categories Hierarchy',
                        'desc' => 'Browse category trees, parent-child relationships and commission rates',
                    ],
                    'categories.manage' => [
                        'label' => 'Create, Edit & Delete Categories',
                        'desc' => 'Manage category slugs, images, icons, and homepage display ordering',
                    ],
                ],
            ],

            'Brand Registry & OEM' => [
                'icon' => 'bi-award-fill',
                'description' => 'Trademark ownership verification, brand rights and OEM protection',
                'permissions' => [
                    'brands.view' => [
                        'label' => 'View Brand Registrations',
                        'desc' => 'Inspect trademark filings, brand documents and registered manufacturers',
                    ],
                    'brands.manage' => [
                        'label' => 'Approve / Reject Trademark Registrations',
                        'desc' => 'Certify registered brand owners and grant intellectual property protections',
                    ],
                ],
            ],

            'Dealer Authorizations' => [
                'icon' => 'bi-patch-check-fill',
                'description' => 'Official distributor certificates and manufacturer channel partners',
                'permissions' => [
                    'dealers.view' => [
                        'label' => 'View Dealer Authorization Requests',
                        'desc' => 'Review distributor applications and manufacturer dealership letters',
                    ],
                    'dealers.manage' => [
                        'label' => 'Approve, Reject & Revoke Dealer Auth',
                        'desc' => 'Issue verified dealer digital certificates or revoke existing ones',
                    ],
                ],
            ],

            'Specifications & Attributes' => [
                'icon' => 'bi-sliders',
                'description' => 'Standardized technical attributes, units of measure and filters',
                'permissions' => [
                    'attributes.view' => [
                        'label' => 'View Standard Attributes',
                        'desc' => 'Browse product attributes (Material, Grade, Size, Voltage, etc.)',
                    ],
                    'attributes.manage' => [
                        'label' => 'Create, Edit & Delete Attributes',
                        'desc' => 'Create attribute groups, pre-set values and input types for categories',
                    ],
                ],
            ],

            'Catalog Requests' => [
                'icon' => 'bi-inbox-fill',
                'description' => 'Vendor requests for new brands, categories or custom spec fields',
                'permissions' => [
                    'catalog_requests.view' => [
                        'label' => 'View Catalog Requests',
                        'desc' => 'Monitor requests from vendors to add missing brands or categories',
                    ],
                    'catalog_requests.manage' => [
                        'label' => 'Approve & Reject Catalog Requests',
                        'desc' => 'Process vendor requests and auto-provision requested taxonomy entities',
                    ],
                ],
            ],

            'B2B Orders & Invoicing' => [
                'icon' => 'bi-receipt-cutoff',
                'description' => 'Commercial purchase orders, dispatch tracking and payments',
                'permissions' => [
                    'orders.view' => [
                        'label' => 'View Orders & Invoices',
                        'desc' => 'Browse orders, itemized lines, buyer/seller info and tax invoices',
                    ],
                    'orders.status' => [
                        'label' => 'Update Order Fulfillment Status',
                        'desc' => 'Change order state (Processing, Shipped, In Transit, Delivered)',
                    ],
                    'orders.payment' => [
                        'label' => 'Update Payment & Financial Status',
                        'desc' => 'Record offline wire transfers, mark paid, partial or dispute status',
                    ],
                    'orders.cancel' => [
                        'label' => 'Cancel Orders & Issue Refunds',
                        'desc' => 'Cancel orders and trigger refund / return workflows',
                    ],
                ],
            ],

            'Quotations & RFQs' => [
                'icon' => 'bi-file-earmark-ruled-fill',
                'description' => 'Request For Quotation (RFQ) workflows and custom bulk pricing',
                'permissions' => [
                    'quotations.view' => [
                        'label' => 'View RFQ Inquiries',
                        'desc' => 'Monitor high-volume quotation requests submitted by buyers',
                    ],
                    'quotations.manage' => [
                        'label' => 'Facilitate & Update Quotation Status',
                        'desc' => 'Review vendor quote responses, edit parameters and update RFQ lifecycle',
                    ],
                ],
            ],

            'Product Samples & Trials' => [
                'icon' => 'bi-box2-heart-fill',
                'description' => 'Free and paid material specimen testing before bulk order',
                'permissions' => [
                    'samples.view' => [
                        'label' => 'View Sample Requests',
                        'desc' => 'Inspect buyer sample applications and required sample specs',
                    ],
                    'samples.manage' => [
                        'label' => 'Manage Sample Approvals & Dispatches',
                        'desc' => 'Approve samples, add dispatch tracking details and mark delivered',
                    ],
                ],
            ],

            'Logistics & Fleet' => [
                'icon' => 'bi-truck',
                'description' => 'Delivery partners, shipping rates and fleet providers',
                'permissions' => [
                    'logistics.view' => [
                        'label' => 'View Delivery Partners & Rates',
                        'desc' => 'View integrated carriers, freight rates and service areas',
                    ],
                    'logistics.manage' => [
                        'label' => 'Manage Carriers & Shipping Tariffs',
                        'desc' => 'Toggle active carriers, update base rates and fuel surcharge settings',
                    ],
                ],
            ],

            'Boost Campaigns & Ads' => [
                'icon' => 'bi-rocket-takeoff-fill',
                'description' => 'Vendor sponsored product boost campaigns and paid promotions',
                'permissions' => [
                    'marketing.view' => [
                        'label' => 'View Marketing Campaigns',
                        'desc' => 'Track vendor promotional campaigns, reach and ad spend',
                    ],
                    'marketing.manage' => [
                        'label' => 'Approve Payments & Activate Boosts',
                        'desc' => 'Verify vendor promotional payments and activate featured visibility',
                    ],
                ],
            ],

            'Subscriptions & Tiers' => [
                'icon' => 'bi-patch-check-fill',
                'description' => 'Vendor subscription plans, tier benefits and recurring memberships',
                'permissions' => [
                    'subscriptions.view' => [
                        'label' => 'View Subscribers & Plans',
                        'desc' => 'Browse vendor subscriptions, validity dates and tier entitlements',
                    ],
                    'subscriptions.manage' => [
                        'label' => 'Approve Subscriptions & Edit Plans',
                        'desc' => 'Manually assign plans, approve bank proofs and edit tier pricing',
                    ],
                ],
            ],

            'Password Reset Requests' => [
                'icon' => 'bi-key-fill',
                'description' => 'Offline / Demo password resets for vendors and staff',
                'permissions' => [
                    'password_requests.view' => [
                        'label' => 'View Password Requests',
                        'desc' => 'View requested resets from locked-out sellers and buyers',
                    ],
                    'password_requests.manage' => [
                        'label' => 'Approve & Issue Temporary Credentials',
                        'desc' => 'Generate and send temporary secure passwords to requesting users',
                    ],
                ],
            ],

            'Notifications Center' => [
                'icon' => 'bi-bell-fill',
                'description' => 'Platform alerts, order updates and operational notifications',
                'permissions' => [
                    'notifications.view' => [
                        'label' => 'View Notifications',
                        'desc' => 'Read platform alerts, stock warnings and seller notifications',
                    ],
                    'notifications.manage' => [
                        'label' => 'Manage & Clear Notifications',
                        'desc' => 'Mark notifications as read or clear notification center',
                    ],
                ],
            ],

            'Homepage Merchandising' => [
                'icon' => 'bi-layout-text-window-reverse',
                'description' => 'Banners, hero promotional cards, trending carousels & layouts',
                'permissions' => [
                    'homepage.view' => [
                        'label' => 'View Homepage Merchandising',
                        'desc' => 'Preview hero banners, quick promo cards and layout configurations',
                    ],
                    'homepage.manage' => [
                        'label' => 'Update Banners, Promos & Merchandising',
                        'desc' => 'Upload banners, re-order promo sections and sync featured showcase',
                    ],
                ],
            ],

            'Platform Settings' => [
                'icon' => 'bi-sliders2',
                'description' => 'Global business settings, commission percentages and platform rules',
                'permissions' => [
                    'settings.view' => [
                        'label' => 'View Marketplace Settings',
                        'desc' => 'Inspect company profile, contact details, currency and platform fees',
                    ],
                    'settings.edit' => [
                        'label' => 'Update Platform Settings & Policies',
                        'desc' => 'Modify business parameters, marketplace policies and contact info',
                    ],
                ],
            ],

            'Subadmins & Staff RBAC' => [
                'icon' => 'bi-person-badge-fill',
                'description' => 'Manage staff accounts, login credentials and access rights',
                'permissions' => [
                    'subadmins.view' => [
                        'label' => 'View Subadmins & Staff',
                        'desc' => 'Browse all staff login accounts and their current permissions',
                    ],
                    'subadmins.create' => [
                        'label' => 'Create New Subadmin Accounts',
                        'desc' => 'Create login ID, set password, and configure allowed access permissions',
                    ],
                    'subadmins.edit' => [
                        'label' => 'Modify Subadmins & Permissions',
                        'desc' => 'Edit profile, change password, toggle status and adjust permission matrix',
                    ],
                    'subadmins.delete' => [
                        'label' => 'Deactivate / Delete Subadmins',
                        'desc' => 'Revoke credentials and permanently delete subadmin accounts',
                    ],
                ],
            ],
        ];
    }

    /**
     * Pre-defined fast role templates for convenient 1-click selection.
     */
    public static function getPresets(): array
    {
        return [
            [
                'id' => 'full_subadmin',
                'name' => 'Full Subadmin (All Modules)',
                'badge' => 'Full Access',
                'badge_class' => 'bg-success',
                'description' => 'Unrestricted access to all marketplace operational modules (excluding deleting Super Admin).',
                'permissions' => [
                    'dashboard.view',
                    'vendors.view', 'vendors.approve', 'vendors.edit', 'vendors.kyc', 'vendors.delete',
                    'customers.view', 'customers.edit', 'customers.delete',
                    'products.view', 'products.create', 'products.edit', 'products.approve', 'products.delete', 'products.flags',
                    'categories.view', 'categories.manage',
                    'brands.view', 'brands.manage',
                    'dealers.view', 'dealers.manage',
                    'attributes.view', 'attributes.manage',
                    'catalog_requests.view', 'catalog_requests.manage',
                    'orders.view', 'orders.status', 'orders.payment', 'orders.cancel',
                    'quotations.view', 'quotations.manage',
                    'samples.view', 'samples.manage',
                    'logistics.view', 'logistics.manage',
                    'marketing.view', 'marketing.manage',
                    'subscriptions.view', 'subscriptions.manage',
                    'password_requests.view', 'password_requests.manage',
                    'notifications.view', 'notifications.manage',
                    'homepage.view', 'homepage.manage',
                    'settings.view', 'settings.edit',
                    'subadmins.view', 'subadmins.create', 'subadmins.edit',
                ],
            ],
            [
                'id' => 'catalog_manager',
                'name' => 'Catalog & Vendor Manager',
                'badge' => 'Catalog Ops',
                'badge_class' => 'bg-primary',
                'description' => 'Focuses on vendor onboarding, product moderation, catalog requests, categories and attributes.',
                'permissions' => [
                    'dashboard.view',
                    'vendors.view', 'vendors.approve', 'vendors.edit', 'vendors.kyc',
                    'products.view', 'products.create', 'products.edit', 'products.approve', 'products.flags',
                    'categories.view', 'categories.manage',
                    'brands.view', 'brands.manage',
                    'dealers.view', 'dealers.manage',
                    'attributes.view', 'attributes.manage',
                    'catalog_requests.view', 'catalog_requests.manage',
                    'notifications.view',
                ],
            ],
            [
                'id' => 'order_operations',
                'name' => 'Order & Logistics Executive',
                'badge' => 'Fulfillment',
                'badge_class' => 'bg-info',
                'description' => 'Handles B2B purchase orders, quotation RFQs, sample testing dispatches and freight logistics.',
                'permissions' => [
                    'dashboard.view',
                    'orders.view', 'orders.status', 'orders.payment',
                    'quotations.view', 'quotations.manage',
                    'samples.view', 'samples.manage',
                    'logistics.view', 'logistics.manage',
                    'notifications.view',
                ],
            ],
            [
                'id' => 'customer_support',
                'name' => 'Customer Support & Inquiries',
                'badge' => 'Support Desk',
                'badge_class' => 'bg-warning text-dark',
                'description' => 'Manages buyers, assists with quotations, samples, offline password resets and notifications.',
                'permissions' => [
                    'dashboard.view',
                    'customers.view', 'customers.edit',
                    'quotations.view',
                    'samples.view',
                    'password_requests.view', 'password_requests.manage',
                    'notifications.view',
                ],
            ],
            [
                'id' => 'merchandising_marketing',
                'name' => 'Marketing & Storefront Merchandiser',
                'badge' => 'Merchandising',
                'badge_class' => 'bg-purple',
                'description' => 'Controls homepage hero banners, quick promo cards, promotional campaigns and product badges.',
                'permissions' => [
                    'dashboard.view',
                    'products.view', 'products.flags',
                    'homepage.view', 'homepage.manage',
                    'marketing.view', 'marketing.manage',
                    'subscriptions.view',
                ],
            ],
        ];
    }

    /**
     * Ensure database table exists and all permissions are seeded safely.
     */
    public static function ensureSystemReady(): void
    {
        // 1. Ensure admin_permissions pivot table exists
        try {
            if (!Schema::hasTable('admin_permissions')) {
                Schema::create('admin_permissions', function (Blueprint $table) {
                    $table->unsignedBigInteger('admin_id');
                    $table->unsignedBigInteger('permission_id');
                    $table->primary(['admin_id', 'permission_id']);
                    $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete();
                    $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
                });
            }
        } catch (\Throwable $e) {
            // Log or ignore if cannot execute DDL
        }

        // 2. Ensure all permissions exist in database
        try {
            $modules = self::getModuleDefinitions();
            foreach ($modules as $moduleName => $data) {
                foreach ($data['permissions'] as $permKey => $permInfo) {
                    Permission::firstOrCreate(
                        ['name' => $permKey],
                        [
                            'display_name' => $permInfo['label'],
                            'module'       => $moduleName,
                            'description'  => $permInfo['desc'] ?? null,
                            'guard_name'   => 'admin',
                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            // Fail gracefully if DB unavailable
        }
    }
}
