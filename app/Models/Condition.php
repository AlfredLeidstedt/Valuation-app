<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ValuationHistory;


class Condition extends Model
{
 
    use HasFactory;

    protected $fillable = [

        'name',
        'description',
        'deduction',

        ];

    public function valuationHistories(): HasMany
        {
            return $this->hasMany(ValuationHistory::class);
        }


}
