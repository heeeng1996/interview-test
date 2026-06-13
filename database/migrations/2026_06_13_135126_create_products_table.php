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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('category_id')->constrained('categories', 'uuid')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('description', 100)->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(0);
            $table->string('image', 255)->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('review')->default(0);
            $table->timestamps();

            $table->softDeletes();
        });

        Schema::create('product_supplier', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('product_id')->constrained('products', 'uuid')->onDelete('cascade');
            $table->foreignUuid('supplier_id')->constrained('suppliers', 'uuid')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_supplier');
        Schema::dropIfExists('products');
    }
};
