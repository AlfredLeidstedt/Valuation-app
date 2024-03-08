<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\JsonDataController;



// This endpoint is created to try and make rule classes.
// Route::post('/rules/create', [RuleController::class, 'create']);

// This endpoint is created so that I can try to make a call to wayke and see if I get a response. It is working.
Route::get('get-data', [ApiController::class, 'getData']);


// This endpoint is created so that I can have an endpoint that can fetch all the conditions from the database.
// This endpoint is also protected by the sanctum middleware.
// To be able to reach a protected endpoint you need to have a token that can be generated through the login endpoint. 
// The token should be pasted in the Authorization header in the request as a Bearer token.
Route::middleware('auth:sanctum')->get('get-conditions', [ApiController::class, 'getConditions']);


// This endpoint is created so that I can have an endpoint that can fetch all the rules from the database.
Route::get('get-rules', [ApiController::class, 'getRules']);

// This endpoint is created so that I can have an endpoint that can count the number of set values.
Route::get('get-values', [ApiController::class, 'checkHowManyValuesAreSet']);

// This endpoint is created so that I can have an endpoint that can fetch all the deductions from the database.
Route::get('get-deductions', [ApiController::class, 'getDeductions']);

// This endpoint is created to make a call to make a complete valuation of the car. THIS IS IN DEVELOPMENT
Route::get('valuate-car', [ApiController::class, 'getValuation']);

// This endpoint is created so that I can have an endpoint that can fetch all the conditions from the database.
Route::get('get-condition-deduction-value-by-id', [ApiController::class, 'getConditionDeductionById']);

// This endpoint should return a JSON object from the Wayke API of the CAR.
Route::get('get-car-from-wayke', [ApiController::class, 'getCarFromWayke']);


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// THIS ONE IS NEW AND IS CONNECTED TO SANCTUM:

Route::middleware('auth:sanctum')->get('usersanctum', function (Request $request) {
    return $request->user();
});

Route::post('register', 'App\Http\Controllers\AuthController@register');

Route::post('login', 'App\Http\Controllers\AuthController@login');



// This mappes the route or the endpoint which you can use to be able to access the request in the body of the route. 
// In this case that route points to the Controller class and the method called 'index'.
Route::get('products', [ProductController::class,'index']);

Route::get('showProducts', [ProductController::class,'showProductsInList']);

Route::get('showProductAndPriceOnly/{id}', [ProductController::class,'ProductAndPrice']);


//THIS ENDPOINT IS WORKING AND IT'S TAKING THE ID AND RETURN THE PRODUCT. 
Route::apiResource('products', ProductController::class);

// To save json data. 
Route::put('saveJsonData', [JsonDataController::class, 'storeJsonData']);






