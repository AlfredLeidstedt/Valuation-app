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
        Schema::create('rules', function (Blueprint $table) {
            
            // AUTO GENERATED PROPERTIES: 
            $table->id();
            //$table->timestamps();

            // INFORMATIVE PROPERTIES: 
            $table->string('nameOfRule')->required();

            // INFORMATION-PROPERTIES REGARDING THE REQUIREMENTS OF THE RULE. TO BE COMPARED with TO USEDATA from WAYKE.  
            $table->string('manufacturer')->required();

            $table->decimal('minKm')->nullable();
            $table->decimal('maxKm')->nullable();

            $table->string('modelSeries')->nullable();

            $table->json('hasTowBar')->nullable();
            
            // This should be a json object. Not sure if it will work.
            $table->json('fuelType')->nullable();

            $table->json('gearboxType')->nullable();
            $table->string('equipmentLevel')->nullable();

            $table->decimal('minModelYear')->nullable();
            $table->decimal('maxModelYear')->nullable();

            $table->decimal('minEnginePower')->nullable();
            $table->decimal('maxEnginePower')->nullable();

            $table->decimal('minManufactureYear')->nullable();
            $table->decimal('maxManufactureYear')->nullable();

            // PROPERTIES TO REGULATE WHEN RULE SHOULD BE APPLIED
            $table->boolean('isScheduled')
            ->nullable()
            ->default(false);

            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            
            // PROPERTY TO SEE IF RULE IS PUBLISHED
            $table->boolean('isPublished')
            ->nullable()
            ->default(false);

            // PROPERTY TO SEE IF RULE IS ACTIVE
            $table->boolean('isActive')
            ->nullable()
            ->default(false);
            
            // PROPERTIES THAT ARE SET DEPENDING ON THE INFORMATION-PROPERTIES:
            $table->boolean('isContender')
            ->required()
            ->default(true);

            // Property that set the number of set values, and is used to sort the rules. 
            $table->integer('numberOfSetValues')
            ->default(0);

            // FOREIGN-KEY PROPERTIES THAT ARE SET DEPENDING ON THE INFORMATION-PROPERTIES:
            // $table->foreignId('deduction_id')
            // ->constrained('deductions')
            // ->cascadeOnDelete();

            // Test to connect Rule with CarModels. 
            $table->unsignedBigInteger('car_model_id')
            ->nullable();
            $table->foreign('car_model_id')->references('id')->on('car_models');
            $table->timestamps();
            

        });
    }


        /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
