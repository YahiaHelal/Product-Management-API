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
            $table->string('name');
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable(); // should be < price
            $table->unsignedInteger('stock'); // can't be negative
            $table->string('brand');
            $table->string('main_image_path')->nullable();
            $table->boolean('status')->default(true); // active / inactive [show active hide inactive]

            $table->foreignId('category_id')
                    ->constrained()
                    ->cascadeOnDelete();

            $table->timestamps();

            $table->index('sku');
            $table->index('brand');
            $table->index('status');

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
