<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('companion_profiles', function (Blueprint $table) {
            $table->string('country')->nullable()->after('is_featured');
            $table->string('state')->nullable()->after('country');
            $table->string('city')->nullable()->after('state');
            $table->string('area')->nullable()->after('city');
            $table->decimal('latitude', 10, 8)->nullable()->after('area');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    public function down(): void {
        Schema::table('companion_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'state',
                'city',
                'area',
                'latitude',
                'longitude'
            ]);
        });
    }
};
