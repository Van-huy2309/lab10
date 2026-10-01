<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('voucher_code');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('color_hex', 7)->default('#000000')->after('color');
            $table->decimal('price', 12, 0)->nullable()->after('color_hex');
            $table->string('image')->nullable()->after('stock');
            $table->dropColumn('size');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size')->default('')->after('color');
            $table->dropColumn(['color_hex', 'price', 'image']);
        });
    }
};
