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
        Schema::create('valuation_histories', function (Blueprint $table) {
            $table->id();

            // Foreign key. 
            $table->unsignedBigInteger('condition_id')->nullable();
            $table->foreign('condition_id')->references('id')->on('conditions');

            // Foreign key. 
            $table->unsignedBigInteger('rule_id')->nullable();
            $table->foreign('rule_id')->references('id')->on('rules');

            // Foreign key. 
            $table->unsignedBigInteger('deduction_id')->nullable();
            $table->foreign('deduction_id')->references('id')->on('deductions');

            $table->integer('valuation_from_wayke')
            ->nullable();

            $table->integer('offer_from_bilbolaget')
            ->nullable();

            $table->string('regNo');

            $table->string('manufacturer');
            
            $table->string('modelSeries');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valuation_histories');
    }
};
