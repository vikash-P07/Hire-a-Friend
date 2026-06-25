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
        // 1. Rename earnings to partner_earnings
        if (Schema::hasTable('earnings') && !Schema::hasTable('partner_earnings')) {
            Schema::rename('earnings', 'partner_earnings');
        }

        // 2. Rename withdrawals to withdrawal_requests
        if (Schema::hasTable('withdrawals') && !Schema::hasTable('withdrawal_requests')) {
            Schema::rename('withdrawals', 'withdrawal_requests');
        }

        // 3. Add payout method and UPI details to withdrawal_requests
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('withdrawal_requests', 'payout_method')) {
                $table->string('payout_method')->default('bank_transfer'); // bank_transfer, upi
                $table->string('upi_id')->nullable();
            }
        });

        // 4. Create commissions table (for custom rates)
        if (!Schema::hasTable('commissions')) {
            Schema::create('commissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('partner_id')->nullable()->constrained('users')->onDelete('cascade'); // Null means global override template
                $table->decimal('commission_percentage', 5, 2)->default(20.00); // Platform defaults to 20%
                $table->timestamps();
            });
        }

        // 5. Create payouts table (funds actually processed/sent)
        if (!Schema::hasTable('payouts')) {
            Schema::create('payouts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('withdrawal_request_id')->nullable()->constrained('withdrawal_requests')->onDelete('cascade');
                $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->string('payout_method'); // bank_transfer, upi
                $table->json('bank_details')->nullable();
                $table->json('upi_details')->nullable();
                $table->string('status')->default('completed'); // completed, failed
                $table->string('transaction_reference')->nullable();
                $table->timestamps();
            });
        }

        // 6. Create refunds table
        if (!Schema::hasTable('refunds')) {
            Schema::create('refunds', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
                $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->string('refund_status')->default('completed'); // pending, completed, failed
                $table->string('refund_transaction_id')->unique();
                $table->text('reason')->nullable();
                $table->timestamps();
            });
        }

        // 7. Create payment_transactions table for gateway logs
        if (!Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
                $table->string('payment_gateway'); // e.g. Razorpay, Stripe, MockGateway
                $table->string('gateway_transaction_id')->unique();
                $table->json('response_payload')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('commissions');

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropColumn(['payout_method', 'upi_id']);
        });

        if (Schema::hasTable('withdrawal_requests') && !Schema::hasTable('withdrawals')) {
            Schema::rename('withdrawal_requests', 'withdrawals');
        }

        if (Schema::hasTable('partner_earnings') && !Schema::hasTable('earnings')) {
            Schema::rename('partner_earnings', 'earnings');
        }
    }
};
