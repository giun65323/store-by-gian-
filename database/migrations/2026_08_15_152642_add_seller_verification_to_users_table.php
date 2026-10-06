<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ktp_photo')->nullable()->after('is_active');
            $table->string('selfie_photo')->nullable()->after('ktp_photo');
            $table->enum('seller_status', ['pending', 'approved', 'rejected'])
                  ->nullable()->after('selfie_photo');
            $table->timestamp('seller_applied_at')->nullable()->after('seller_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ktp_photo', 'selfie_photo', 'seller_status', 'seller_applied_at']);
        });
    }
};
