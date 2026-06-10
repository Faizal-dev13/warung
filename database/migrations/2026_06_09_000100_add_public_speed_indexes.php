<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->index(['is_active', 'sort_order'], 'products_public_active_sort_idx');
                $table->index(['category_id', 'is_active'], 'products_public_category_idx');
                $table->index(['is_featured', 'is_active'], 'products_public_featured_idx');
                $table->index(['created_at'], 'products_public_created_idx');
            });
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->index(['is_active', 'sort_order'], 'categories_public_active_sort_idx');
            });
        }

        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->index(['is_active', 'sort_order'], 'banners_public_active_sort_idx');
            });
        }

        if (Schema::hasTable('vouchers')) {
            Schema::table('vouchers', function (Blueprint $table) {
                $table->index(['is_active', 'starts_at', 'ends_at'], 'vouchers_public_active_period_idx');
            });
        }

        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->index(['product_id', 'is_active', 'stock'], 'variants_public_stock_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('product_variants')) {
            Schema::table('product_variants', fn (Blueprint $table) => $table->dropIndex('variants_public_stock_idx'));
        }

        if (Schema::hasTable('vouchers')) {
            Schema::table('vouchers', fn (Blueprint $table) => $table->dropIndex('vouchers_public_active_period_idx'));
        }

        if (Schema::hasTable('banners')) {
            Schema::table('banners', fn (Blueprint $table) => $table->dropIndex('banners_public_active_sort_idx'));
        }

        if (Schema::hasTable('categories')) {
            Schema::table('categories', fn (Blueprint $table) => $table->dropIndex('categories_public_active_sort_idx'));
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_public_active_sort_idx');
                $table->dropIndex('products_public_category_idx');
                $table->dropIndex('products_public_featured_idx');
                $table->dropIndex('products_public_created_idx');
            });
        }
    }
};
