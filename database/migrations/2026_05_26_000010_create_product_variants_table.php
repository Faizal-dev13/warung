<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('name', 120);
                $table->string('sku', 80)->nullable()->unique();
                $table->string('duration', 80)->nullable();
                $table->unsignedBigInteger('price')->default(0);
                $table->unsignedBigInteger('old_price')->default(0);
                $table->unsignedInteger('stock')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index(['product_id', 'is_active', 'sort_order']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
