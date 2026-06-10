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
                if (! Schema::hasColumn('products', 'image_path')) {
                    $table->string('image_path')->nullable()->after('badge');
                }
            });
        }

        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table) {
                if (! Schema::hasColumn('banners', 'image_path')) {
                    $table->string('image_path')->nullable()->after('subtitle');
                }

                if (! Schema::hasColumn('banners', 'mobile_image_path')) {
                    $table->string('mobile_image_path')->nullable()->after('image_path');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'image_path')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('image_path');
            });
        }

        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table) {
                if (Schema::hasColumn('banners', 'mobile_image_path')) {
                    $table->dropColumn('mobile_image_path');
                }

                if (Schema::hasColumn('banners', 'image_path')) {
                    $table->dropColumn('image_path');
                }
            });
        }
    }
};
