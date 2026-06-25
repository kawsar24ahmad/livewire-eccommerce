<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Populate colors table with proper columns
        Schema::table('colors', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
            $table->string('hex_code', 7)->nullable()->after('name');
        });

        // 2) Add FKs + variant image to product_variants, drop legacy JSON
        Schema::table('product_variants', function (Blueprint $table) {
            $table->foreignId('color_id')->nullable()->after('product_id')
                ->constrained()->nullOnDelete();
            $table->foreignId('size_id')->nullable()->after('color_id')
                ->constrained()->nullOnDelete();
            $table->string('image_path')->nullable()->after('compare_price');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('options');
        });

        // 3) product_images already has product_variant_id FK from earlier migration — no change needed
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->json('options')->nullable();
            $table->dropForeign(['color_id']);
            $table->dropForeign(['size_id']);
            $table->dropColumn(['color_id', 'size_id', 'image_path']);
        });

        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn(['name', 'hex_code']);
        });
    }
};