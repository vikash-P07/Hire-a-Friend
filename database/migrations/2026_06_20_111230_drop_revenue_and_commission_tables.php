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
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('commissions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('global');
            $table->unsignedBigInteger('target_id')->nullable();
            $table->decimal('rate_percentage', 5, 2)->default(10.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('partner_id');
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();
        });
    }
};
