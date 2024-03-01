<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Rule; // Import the Rule class

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deductions', function (Blueprint $table) {

            // Auto generated properties
            $table->id();
            $table->timestamps();

            // Informative properties
            $table->string('name');

            // This property will be set through the data that is recieved from Wayke.
            // $table->unsignedBigInteger('valuationOfCar')
            // ->default(100000);

            // Amount of deduction. Four decimal places
            $table->decimal('deductionPercentage', 10, 3);

            // The estimated deduction. This will be calculated by the application.
            $table->decimal('estimatedDeduction', 14)
            ->nullable()
            ->default(10000);

            // Constraints. At what interval of car-valuation should this deduction % be applied to?
            $table->unsignedBigInteger('minValueInterval');
            $table->unsignedBigInteger('maxValueInterval');

            // Constraints. These will be used to compare with the estimated deduction.
            $table->unsignedBigInteger('minDeduction');
            $table->unsignedBigInteger('maxDeduction');

            // Foreign key, connection to the rule table
            // $table->unsignedBigInteger('rule_id'); // Use the Rule class

            // Define the foreign key
            //$table->foreign('rule_id')->references('id')->on('rules')->onDelete('cascade');

            // Test to try to set relationship to rule table by using relation manager. 
            $table->foreignIdFor(Rule::class);  
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('deductions');

    }
};

