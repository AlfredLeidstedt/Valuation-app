<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Rule;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [

        'make',
        'model',
        'total_count'
        
        ];

    public function rules(): HasMany
        {
            return $this->hasMany(Rule::class);
        }
}
