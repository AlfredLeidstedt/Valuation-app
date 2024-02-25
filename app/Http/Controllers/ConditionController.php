<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConditionResource;
use Illuminate\Http\Request;
use App\Models\Condition;


class ConditionController extends Controller
{
    public function conditions($request, Condition $condition)
    {

        return new ConditionResource($condition);

    }


    
    // This method is created to try to make a call to the database to see if I can get all the different conditions.
    public function getConditions(Request $request)
    {

        $conditions = Condition::all(); 
        
        return response()->json(['conditions' => $conditions], 200);

    }

    
}