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
        // 1. Rename partner_profiles to companion_profiles
        if (Schema::hasTable('partner_profiles') && !Schema::hasTable('companion_profiles')) {
            Schema::rename('partner_profiles', 'companion_profiles');
        }

        // 2. Rename partner_services pivot table to companion_services
        if (Schema::hasTable('partner_services') && !Schema::hasTable('companion_services')) {
            Schema::rename('partner_services', 'companion_services');
        }

        // 3. Drop partner_documents and create documents_verification
        Schema::dropIfExists('partner_documents');
        Schema::create('documents_verification', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('aadhaar_front')->nullable();
            $table->string('aadhaar_back')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('selfie')->nullable();
            $table->string('aadhaar_status')->default('pending'); // pending, approved, rejected
            $table->string('pan_status')->default('pending');
            $table->string('selfie_status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Add onboarding and settings columns to companion_profiles
        Schema::table('companion_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('companion_profiles', 'bank_holder_name')) {
                $table->string('bank_holder_name')->nullable();
                $table->string('bank_account_number')->nullable();
                $table->string('bank_ifsc')->nullable();
                $table->string('bank_name')->nullable();
                $table->text('languages')->nullable();
                $table->text('interests')->nullable();
                $table->boolean('vacation_mode')->default(false);
                $table->boolean('availability_status')->default(true);
            }
        });

        // 5. Create availability table
        Schema::create('availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('day'); // Mon, Tue, etc.
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // 6. Create earnings table
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->string('status')->default('pending'); // pending, cleared, withdrawn
            $table->timestamps();
        });

        // 7. Create withdrawals table
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('bank_holder_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // 8. Create subscriptions table
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->string('status')->default('active'); // active, expired, cancelled
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // 9. Create payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('payment_status')->default('pending'); // pending, completed, failed
            $table->string('transaction_id')->unique();
            $table->string('payable_type')->nullable();
            $table->unsignedBigInteger('payable_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('earnings');
        Schema::dropIfExists('availability');

        Schema::table('companion_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'bank_holder_name',
                'bank_account_number',
                'bank_ifsc',
                'bank_name',
                'languages',
                'interests',
                'vacation_mode',
                'availability_status'
            ]);
        });

        Schema::dropIfExists('documents_verification');

        if (Schema::hasTable('companion_services') && !Schema::hasTable('partner_services')) {
            Schema::rename('companion_services', 'partner_services');
        }

        if (Schema::hasTable('companion_profiles') && !Schema::hasTable('partner_profiles')) {
            Schema::rename('companion_profiles', 'partner_profiles');
        }
    }
};
