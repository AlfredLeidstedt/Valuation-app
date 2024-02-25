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

            $table->foreignId('brand_id')
            ->constrained('brands')
            ->cascadeOnDelete();

            $table->string('name');

            $table->string('slug')->unique();

            $table->string('sku')->unique();

            $table->string('image')->nullable();

            $table->longText('description')->nullable();

            $table->unsignedBigInteger('quantity');

            $table->decimal('price', 10, 2);

            // Following table column contains a spelling mistake. 
            $table->boolean('is_visable')->default(false);

            $table->boolean('is_featured')->default(false);
            
            $table->enum('type', ['deliverable', 'downloadable'])
                ->default('deliverable');

            // In video the following column was named published_at    
            $table->date('published');

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