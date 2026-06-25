<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop existing pivot roles tables
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');

        // 2. Create Countries table
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('currency', 10)->default('INR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Create States table
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('name');
            $table->string('code', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Modify Cities table (add state_id and is_active)
        Schema::table('cities', function (Blueprint $table) {
            if (!Schema::hasColumn('cities', 'state_id')) {
                $table->unsignedBigInteger('state_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('cities', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('slug');
            }
        });

        // 5. Create Banners table
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('homepage'); // homepage, promotional, offer, event, marketing
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });

        // 6. Create Plans table
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('interval')->default('monthly'); // monthly, yearly
            $table->text('features_limit')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 7. Create Coupons table
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type')->default('percentage'); // percentage, flat, cashback, referral
            $table->decimal('value', 10, 2);
            $table->integer('max_uses')->default(100);
            $table->integer('uses_count')->default(0);
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 8. Create Audit Logs table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        // 9. Create Blogs table
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('author_name')->default('Admin');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 10. Create Commissions table
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('global'); // global, country, state, city, partner
            $table->unsignedBigInteger('target_id')->nullable(); // country_id, state_id, city_id, partner_id
            $table->decimal('rate_percentage', 5, 2)->default(10.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 11. Create Withdrawals table
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('notes')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();
        });

        // 12. Add SEO metadata columns to existing cms_pages table
        Schema::table('cms_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('cms_pages', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('content');
            }
            if (!Schema::hasColumn('cms_pages', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('banners');
        
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn(['state_id', 'is_active']);
        });

        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
};
