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
            $table->id();
            $table->string('name'); // Name column for the products table
            $table->text('description')->nullable(); // Description column for the products table
            $table->string('slug')->unique(); // Slug column for the products table
            $table->integer('price'); // Price column for the products table
            $table->integer('stock'); // Stock column for the products table
            $table->string('image'); // Image column for the products table
            $table->foreignId('product_category_id')->constrained('product_categories'); // Foreign key to the product_categories table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
