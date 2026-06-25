<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companion_profiles', function (Blueprint $table) {
            $table->boolean('is_recommended')->default(false)->after('is_featured');
            $table->boolean('is_top_profile')->default(false)->after('is_recommended');
            $table->integer('recommended_order')->default(0)->after('is_top_profile');
            $table->integer('top_profile_order')->default(0)->after('recommended_order');
            $table->boolean('is_recommended_visible')->default(true)->after('top_profile_order');
            $table->boolean('is_top_profile_visible')->default(true)->after('is_recommended_visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companion_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'is_recommended',
                'is_top_profile',
                'recommended_order',
                'top_profile_order',
                'is_recommended_visible',
                'is_top_profile_visible',
            ]);
        });
    }
};
