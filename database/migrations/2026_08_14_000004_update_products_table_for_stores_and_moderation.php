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
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('user_id')->constrained('stores')->onDelete('cascade');
            $table->text('description')->nullable()->after('name');
            $table->enum('status', ['active', 'suspended', 'deleted_by_admin'])->default('active')->after('stock');
            $table->softDeletes()->after('updated_at');

            $table->index('store_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn(['store_id', 'description', 'status', 'deleted_at']);
        });
    }
};
