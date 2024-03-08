<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ValuationHistory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deductionPercentage',
        'estimatedDeduction',
        'minValueInterval',
        'maxValueInterval',
        'minDeduction',
        'maxDeduction',
        //'rule_id',
    ];

    // This is the relationship between the Deduction and Rule models

    // Test to remova and change the relationship to belongsTo
    /*
    public function rule(): BelongsToMany
    {
        return $this-> belongsToMany(Rule::class);
    }
    */

    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    
    public function valuationHistories(): HasMany
        {
            return $this->hasMany(ValuationHistory::class);
        }

}
