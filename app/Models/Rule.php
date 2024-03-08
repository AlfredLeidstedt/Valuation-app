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

    // Properties: 

    protected $table = 'rules';

    protected $casts = [
        'isScheduled' => 'boolean',
        'isPublished' => 'boolean',
        'isActive' => 'boolean',
        'isContender' => 'boolean',
        'hasTowBar' => 'array',
        'fuelType' => 'array',
        'gearboxType' => 'array',
    ];

    protected $fillable = [

        // Name of rule is auto-filled. 
        // numberOfSetValues is set throug a counting method made in the Rule-Resource/Create file. 

        'nameOfRule',
        'manufacturer',
        'minKm',
        'maxKm',
        //'modelSeries',
        'car_model_id',
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

        public $timestamps = true;

        protected $primaryKey = 'id';

        // Relationships: 

        // Use of relationship type with relationManager
        public function deductions(): HasMany
        {
            return $this->hasMany(Deduction::class);
        }

        public function carModel(): BelongsTo
        {
            return $this->belongsTo(CarModel::class);
        }

        public function valuationHistories(): HasMany
        {
            return $this->hasMany(ValuationHistory::class);
        }

        // Methods: 

        public function isApplicable($data, $rule, $carMileageInKm): bool
        {

            if( 
                
            ($data['dataUsed']['manufacturer'] === $rule['manufacturer']) && 

            ($carMileageInKm >= $rule['minKm'] || $rule['minKm'] === null) &&

            ($carMileageInKm <= $rule['maxKm'] || $rule['maxKm'] === null) &&

            ($data['dataUsed']['modelSeries'] === $rule['modelSeries'] || $rule['modelSeries'] === null) &&

            (
            (($data['dataUsed']['hasTowbar'] === true) && (in_array('HasTowbar', $rule['hasTowBar']))) ||
            (($data['dataUsed']['hasTowbar'] === false) && (in_array('HasNoTowbar', $rule['hasTowBar']))) ||
            (empty($rule['hasTowBar'])) 
            ) &&

            (in_array($data['dataUsed']['fuelType'], $rule['fuelType'])) ||
            (empty($rule['fuelType'])) &&

            (in_array($data['dataUsed']['gearboxType'], $rule['gearboxType'])) ||
            (empty($rule['gearboxType'])) &&

            //($data['dataUsed']['equipmentLevel'] === $rule['equipmentLevel'] or $rule['equipmentLevel'] === null) &&

            ($data['dataUsed']['enginePower'] >= $rule['minEnginePower'] || $rule['minEnginePower'] === null) &&

            ($data['dataUsed']['enginePower'] <= $rule['maxEnginePower'] || $rule['maxEnginePower'] === null) &&

            ($data['dataUsed']['manufactureYear'] >= $rule['minManufactureYear'] || $rule['minManufactureYear'] === null) &&

            ($data['dataUsed']['manufactureYear'] <= $rule['maxManufactureYear'] || $rule['maxManufactureYear'] === null) 

            ){
                return true;
            }
            else {
                return false;
            }            
        }
}
