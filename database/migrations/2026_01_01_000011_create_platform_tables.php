<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // BANNERS
        // ---------------------------------------------------------------
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title', 300);
            $table->string('subtitle', 300)->nullable();
            $table->string('image', 500);
            $table->string('mobile_image', 500)->nullable();
            $table->string('link', 500)->nullable();
            $table->enum('link_target', ['_self', '_blank'])->default('_self');
            $table->enum('position', [
                'hero', 'top_promo', 'mid_promo', 'sidebar',
                'category', 'brand', 'popup'
            ])->default('hero');
            $table->string('background_color', 20)->nullable();
            $table->string('text_color', 20)->nullable();
            $table->string('button_text', 100)->nullable();
            $table->string('button_color', 20)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('clicks')->default(0);
            $table->timestamps();

            $table->index(['position', 'status']);
            $table->index(['start_date', 'end_date']);
        });

        // ---------------------------------------------------------------
        // CMS PAGES
        // ---------------------------------------------------------------
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 300);
            $table->string('slug', 350)->unique();
            $table->longText('content');
            $table->string('meta_title', 300)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->boolean('is_in_footer')->default(false);
            $table->boolean('is_in_header')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
        });

        // ---------------------------------------------------------------
        // SETTINGS (key-value store — all platform config)
        // ---------------------------------------------------------------
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 100)->comment('general, mail, payment, seo, social');
            $table->string('key', 200);
            $table->longText('value')->nullable();
            $table->enum('type', [
                'text', 'number', 'boolean', 'json', 'html', 'image', 'file', 'textarea'
            ])->default('text');
            $table->string('label', 300);
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false)->comment('Exposed to frontend JS');
            $table->timestamps();

            $table->unique(['group', 'key']);
            $table->index('group');
            $table->index('is_public');
        });

        // ---------------------------------------------------------------
        // COMMISSION RULES
        // ---------------------------------------------------------------
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->enum('rule_type', ['global', 'category', 'vendor', 'product']);
            $table->unsignedBigInteger('reference_id')->nullable()->comment('category_id, vendor_id, or product_id');
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('commission_value', 10, 2);
            $table->decimal('min_order_amount', 12, 2)->nullable();
            $table->decimal('max_commission', 12, 2)->nullable()->comment('Cap');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('priority')->default(0)->comment('Higher = takes precedence');
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->timestamps();

            $table->index(['rule_type', 'reference_id']);
            $table->index('is_active');
        });

        // ---------------------------------------------------------------
        // PRODUCT VIEWS (analytics)
        // ---------------------------------------------------------------
        Schema::create('product_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer', 500)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('product_id');
            $table->index('created_at');
            $table->index('customer_id');
        });

        // ---------------------------------------------------------------
        // RECENTLY VIEWED
        // ---------------------------------------------------------------
        Schema::create('recently_viewed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('session_id', 100)->nullable();
            $table->unsignedBigInteger('product_id');
            $table->timestamp('viewed_at')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['customer_id', 'product_id'], 'uniq_recent_customer_product');
            $table->index(['customer_id', 'session_id']);
        });

        // ---------------------------------------------------------------
        // NOTIFICATIONS (system notifications for all user types)
        // ---------------------------------------------------------------
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // QUEUE JOBS
        // ---------------------------------------------------------------
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // ---------------------------------------------------------------
        // CACHE
        // ---------------------------------------------------------------
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('recently_viewed');
        Schema::dropIfExists('product_views');
        Schema::dropIfExists('commission_rules');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('banners');
    }
};
