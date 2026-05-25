<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('summary');
            $table->text('description');
            $table->unsignedInteger('price');
            $table->unsignedInteger('old_price')->default(0);
            $table->string('badge')->nullable();
            $table->string('icon')->default('ph-package');
            $table->string('accent')->default('from-blue-600 to-indigo-600');
            $table->json('features')->nullable();
            $table->boolean('is_latest')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
