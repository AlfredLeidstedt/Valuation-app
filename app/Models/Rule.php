<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RuleDeduction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rule extends Model
{
    use HasFactory;

    protected $table = 'rules';

    protected $casts = [
        'isScheduled' => 'boolean',
        'isPublished' => 'boolean',
        'isActive' => 'boolean',
        'isContender' => 'boolean',
        'hasTowBar' => 'boolean',
        'fuelType' => 'array',
        'gearboxType' => 'array',
    ];

    protected $fillable = [

        // Name of rule should be auto-filled. 

        'nameOfRule',
        'manufacturer',
        'minKm',
        'maxKm',
        'modelSeries',
        'hasTowBar',
        'fuelType',
        'gearboxType',
        'equipmentLevel',
        'minModelYear',
        'maxModelYear',
        'minEnginePower',
        'maxEnginePower',
        'minManufactureYear',
        'maxManufactureYear',
        'isScheduled',
        'startdate',
        'enddate',
        'isPublished',
        'isActive',
        'isContender',
        'numberOfSetValues',

        ];

        // If not null ++1 to a counter-property.

        public $timestamps = true;
        protected $primaryKey = 'id';

        /*
        public function deductions(): BelongsToMany
        {

            return $this->belongsToMany(Deduction::class);

        }
        */

        // Test to use another relationship type with relationManager
        public function deductions(): HasMany
        {
            return $this->hasMany(Deduction::class);
        }


        public function createDeduction(Rule $rule, Deduction $deduction): Deduction
        {
                $rule = Rule::create($deduction->getRecord()->toArray());
                
                $deduction = Deduction::create([
                    'rule_id' => $rule->id,
                    'name' => $deduction->get('name'),
                    'deductionMultiplier' => $deduction->get('deductionMultiplier'),
                    'minDeduction' => $deduction->get('minDeduction'),
                    'maxDeduction' => $deduction->get('maxDeduction'),
                ]);

                return $deduction;
        }
       

        
}
