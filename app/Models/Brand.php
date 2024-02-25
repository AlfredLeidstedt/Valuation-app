<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//This import is something that I added that was not in the tutorial:
use Illuminate\Database\Eloquent\Relations\HasMany;


class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'url', 'primary_hex', 'is_visible', 'description'
    ];
    

    // I changed this name from brands to products and then it worked. 
    // Not sure if it was a typo from earlier. 
    public function products(): HasMany 
    {
        return $this->hasMany(Product::class);
    }

}


