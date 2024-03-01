<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\ConditionResource;

use Illuminate\Http\Request;
use App\Models\Condition;
use App\Models\Deduction;
use App\Models\Rule;
use Illuminate\Support\Facades\Cache;



class ApiController extends Controller
{


    // This method is created to try and make a call to wayke and see if I get a response.
    public function getData(Request $request)
    {

        $regNo = $request->input('regNo');
        $mileage = $request->input('mileage');
        $apiKey = $request->header('x-api-key');

        $response = Http::withHeader('x-api-key', $apiKey) -> 
        get('https://api.wayke.se/wip/external/vehicle?regNo=' . urlencode($regNo) . '&km=' . urlencode($mileage * 10), 
        [

            'regNo' => $regNo,
            'milage' => $mileage,

        ]);

        if ($response->getStatusCode() == 200) {

            // Retrieve data from the JSON response from Wayke
            $data = $response->json();
            $manufacturer = $data['dataUsed']['manufacturer'];
            $prediction = $data['price']['prediction'];

            // Evaluate the data from Wayke and return a response
                if($manufacturer === 'Volvo') {

                    $bilbolagetPrediction = $prediction * 0.8;

                    return response()->json(['bilbolagetPrediction' => $bilbolagetPrediction],200);
                } 
                
                else {

                    return response()->json([
                    'message' => 'Manufacturer is not Volvo',
                    'prediction' => $prediction
                    ],200);

                }

            } 
            
            else {

            return response()->json(['error' => 'Failed to fetch data from the external API'], $response->status());

            }


    }

        // This method is created to try and make a call to wayke and see if I get a response.
        public function getCarFromWayke(Request $request)
        {
    
            $regNo = $request->input('regNo');
            $mileage = $request->input('mileage');
            
            $apiKey = $request->header('x-api-key');
    
            $response = Http::withHeader('x-api-key', $apiKey) -> 
            get('https://api.wayke.se/wip/external/vehicle?regNo=' . urlencode($regNo) . '&km=' . urlencode($mileage * 10), 
            [
    
                'regNo' => $regNo,
                'milage' => $mileage,
    
            ]);
    
            if ($response->getStatusCode() == 200) {
    
                // Retrieve data from the JSON response from Wayke
                $data = $response->json();

                return response()->json(['data' => $data],200);
                } 
                
                else {
    
                return response()->json(['error' => 'Failed to fetch data from the external API'], $response->status());
    
                }
    
    
        }
    



    // This method is created to try to make a call to the database to see if I can get all the different conditions.
    // This endpoint is working.
    public function getConditions(Request $request)
    {

        $conditions = Condition::all(); 
        
        return response()->json(['conditions' => $conditions], 200);

    }


    // This method is created to try to make a call to the database to see if I can get all the different rules.
    // This endpoint is working.
    public function getRules(Request $request)
    {

        $rules = Rule::all();
        
        return response()->json(['rules' => $rules], 200);

    }

    
    // This method is created to try to make a call to the database to see if I can get all the different deductions.
    // 
    public function getDeductions(Request $request)
    {

        $deductions = Deduction::all();
        
        return response()->json(['deductions' => $deductions], 200);

    }


    // This method is created to try to make a call to the database to see if I can get a specific deduction value based on the condition-id.
    // This is working.
    public function getConditionDeductionById(Request $request)
    {

        $conditionId = $request->input('conditionId');

        $condition = Condition::find($conditionId);

        $conditionName = $condition['name'];
        

        // Retrieve data from the JSON response from Wayke
        $conditionDeduction = $condition['deduction'];
        

        return response()->json(['Name' => $conditionName, 'Deduction' => $conditionDeduction], 200);

    }

    
    // Make a function to check if each of the properties of the rule is null or not.

    public function checkHowManyValuesAreSet(Rule $rule)
    {

        $values = [
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
            
        ];

        $associativeArray = array_fill_keys($values, null);

        $associativeArray['nameOfRule'] = $rule->nameOfRule;
        $associativeArray['manufacturer'] = $rule->manufacturer;
        $associativeArray['minKm'] = $rule->minKm;
        $associativeArray['maxKm'] = $rule->maxKm;
        $associativeArray['modelSeries'] = $rule->modelSeries;
        $associativeArray['hasTowBar'] = $rule->hasTowBar;
        $associativeArray['fuelType'] = $rule->fuelType;
        $associativeArray['gearboxType'] = $rule->gearboxType;
        $associativeArray['equipmentLevel'] = $rule->equipmentLevel;
        $associativeArray['minModelYear'] = $rule->minModelYear;
        $associativeArray['maxModelYear'] = $rule->maxModelYear;
        $associativeArray['minEnginePower'] = $rule->minEnginePower;
        $associativeArray['maxEnginePower'] = $rule->maxEnginePower;
        $associativeArray['minManufactureYear'] = $rule->minManufactureYear;
        $associativeArray['maxManufactureYear'] = $rule->maxManufactureYear;
        $associativeArray['isScheduled'] = $rule->isScheduled;
        $associativeArray['startdate'] = $rule->startdate;
        $associativeArray['enddate'] = $rule->enddate;
        $associativeArray['isPublished'] = $rule->isPublished;
        $associativeArray['isActive'] = $rule->isActive;
        $associativeArray['isContender'] = $rule->isContender;

        foreach ($associativeArray as $key => $value) {

            if ($value !== null) {

                // Property is not null, increment the counter
                $rule->numberOfSetValues = $rule->numberOfSetValues++;

            }

        }

        return $rule -> numberOfSetValues;

    }
    


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    // Make an endpoint to make a complete valuation of the car.

    public function getValuation(Request $request)
    {


        // I need to get the REG. NUMBER and the MILAGE from the request.
        $regNo = $request->input('regNo');
        $mileage = $request->input('mileage');

        $carMileageInKm = $mileage * 10;

        // This is the variable that I will use to store the car-data from Wayke.
        $data = null;

        // I need to check if this regNo is saved in the cache. If it is I need to return the estimation-value from the cache that is from Wayke.

        $cachedDataOnCarFromWayke = Cache::get($regNo);


        // If the value is not null I need to assign the value to the variable $cachedDataOnCarFromWayke so I can use it in further calculations. 
        
        if ($cachedDataOnCarFromWayke !== null) {
            
            $data = $cachedDataOnCarFromWayke;

            echo "The valuation is calculated using a cached data from Wayke. \n";

        }
        

        // If the value is null I need to get the value from Wayke and then save it in the cache for future use.
        else {

            // I then need to pass this to an endpoint that returns the car object from Wayke (including the prediction price).
            $apiKey = $request->header('x-api-key');

            $response = Http::withHeader('x-api-key', $apiKey) -> 
            get('https://api.wayke.se/wip/external/vehicle?regNo=' . urlencode($regNo) . '&km=' . urlencode($mileage * 10), 
            [

                'regNo' => $regNo,
                'milage' => $mileage,

            ]);


            // Retrieve data from the response from Wayke and parse it to JSON.
            $data = $response->json();

            // Before I continue I want to save the valuation data that I have retrieved from Wayke so that I don't have to use Wayke to get that data if the same regNo is 
            // requesting a valuation again.
            // I do not know how long I should set the cache to be valid. I will set it to 30 days for now.
            Cache::put($regNo, $data, now()->addDays(30));


        }


        // After I have assigned the value to $estimatedValueFromWayke either through the cahe, or through a call to Wayke, I need to continue with the valuation.


        // Then I also need to get the CONDITION ID from the customers submit. 
        $conditionId = $request->input('conditionId');

        // With this condition id I need to get that conditions deduction value from the database. 
        $condition = Condition::find($conditionId);
        $conditionDeduction = $condition['deduction'];

        // Then I need to check for rules that are applicable for the car By looking at the RULE-TABLE and 
        // checking to see if the constraints match the values of the car that I have gotten from the request. 

        // First I need to get all the rules from the database.
        $rules = Rule::all();

        /////////////////////////////////////////////////////////////////////////

        // This sorting makes it possible for the rules with the most set values to be first in the collection.
        // This way the first match will be the best match.   
        $sortedRules = $rules->sortByDesc('numberOfSetValues');

        /////////////////////////////////////////////////////////////////////////
        // OWN METHOD    

        // Then I need to loop through all the rules and check if the constraints match the values of the car.
        foreach($sortedRules as $rule) {

            // Assign the properties to variables so that I can use them in the if-statement.
            // I need to get the constraints from the RULE.

            /////////////////////////////////////////////////////////////////////////
            $ruleManufacturer = $rule['manufacturer'];

            $ruleMinKm = $rule['minKm'];
            $ruleMaxKm = $rule['maxKm'];

            $ruleModelSeries = $rule['modelSeries'];

            // The following are arrays. I need to figure out how to check if the constraints match the values of the car.
            $ruleHasTowBar = $rule['hasTowBar'];
            $ruleFuelType = $rule['fuelType'];
            $ruleGearboxType = $rule['gearboxType'];

            $ruleEquipmentLevel = $rule['equipmentLevel'];

            // $ruleMinModelYear = $rule['minModelYear'];
            // $ruleMaxModelYear = $rule['maxModelYear'];

            $ruleMinEnginePower = $rule['minEnginePower'];
            $ruleMaxEnginePower = $rule['maxEnginePower'];
            $ruleMinManufactureYear = $rule['minManufactureYear'];
            $ruleMaxManufactureYear = $rule['maxManufactureYear'];

            // Step 2 conditions:
            $ruleIsScheduled = $rule['isScheduled'];
            $ruleStartDate = $rule['startdate'];
            $ruleEndDate = $rule['enddate'];

            $ruleIsPublished = $rule['isPublished'];
            $ruleIsActive = $rule['isActive'];
            $ruleIsContender = $rule['isContender'];

            /////////////////////////////////////////////////////////////////////////

            // I need to get the values from the CAR object from WAYKE.
            $carManufacturer = $data['dataUsed']['manufacturer'];
            $carModelSeries = $data['dataUsed']['modelSeries'];
            
            $carHasTowbar = $data['dataUsed']['hasTowbar']; // true or false
            $carFuelType = $data['dataUsed']['fuelType'];
            $carGearboxType = $data['dataUsed']['gearboxType'];

            $carEquipmentLevel = $data['dataUsed']['equipmentLevel'];

            $carEngninePower = $data['dataUsed']['enginePower'];
            $carManufactureYear = $data['dataUsed']['manufactureYear'];


            // I need to check if the constraints from the rule match the values of the car.
            // I also need to check if the rule is active, published or scheduled. 
            // I also need to check if the rule makes a contender.
        if(

            // COMPARE CONSTRAINTS OF THE CAR WITH RULES:
            $carManufacturer === $ruleManufacturer && 
            ($carMileageInKm >= $ruleMinKm or $ruleMinKm === null) &&
            ($carMileageInKm <= $ruleMaxKm or $ruleMaxKm === null) &&
            ($carModelSeries === $ruleModelSeries or $ruleModelSeries === null) &&
            //($carHasTowbar === $ruleHasTowBar or $ruleHasTowBar === null) &&

            (
            (($carHasTowbar === true) && (in_array('HasTowbar', $ruleHasTowBar))) ||
            (($carHasTowbar === false) && (in_array('HasNoTowbar', $ruleHasTowBar))) ||
            (empty($ruleHasTowBar)) 
            ) &&

            (in_array($carFuelType, $ruleFuelType)) &&
            (in_array($carGearboxType, $ruleGearboxType)) &&

            ($carEquipmentLevel === $ruleEquipmentLevel or $ruleEquipmentLevel === null) &&
            ($carEngninePower >= $ruleMinEnginePower or $ruleMinEnginePower === null) &&
            ($carEngninePower <= $ruleMaxEnginePower or $ruleMaxEnginePower === null) &&
            ($carManufactureYear >= $ruleMinManufactureYear or $ruleMinManufactureYear === null) &&
            ($carManufactureYear <= $ruleMaxManufactureYear or $ruleMaxManufactureYear === null) 


            ) {

                $today = date("Y-m-d");

                if (         

                    // CHECK IF THE RULE IS ACTIVE, PUBLISHED OR SCHEDULED:
                    $ruleIsActive === true &&
                    $ruleIsPublished === true &&
                    (($ruleIsScheduled === true && ($today >= $ruleStartDate && $today <= $ruleEndDate)) or $ruleIsScheduled === false) &&
                    $ruleIsContender === true

                    ) {

                // If the constraints match AND the rule is active , I need to check the deductions related to the rule to see if it matches. 

                // I need to get the deduction id from the rule to be able to get the deduction value from the deduction table.                

                $deductionId = DB::select('select id from deductions where rule_id = :id', ['id' => $rule['id']]);
                // The above line is returning an array of id:s if there are more then one deduction with the same rule_id.
                  
                $deductionValues = [];

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                $deductionIdData = json_decode(json_encode($deductionId), true);

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // Here I need to make sure that it checks all the deductions that have the same rule_id and not only one. And return the deducton with the highest value.
                foreach($deductionIdData as $deductionId) {
                
                    // I need to get the deduction object from the deduction table.
                    $deduction = Deduction::find($deductionId);

                    // I need to parse the $deduction to a JSON object.
                    $data1 = json_decode($deduction, true);

                    // To be able to find the deduction percentage I need to go inte to the first object [0] and then get the deductionPercentage.
                    $deductionPercentage = $data1[0]['deductionPercentage'];

                    // Parse the $deductionPercentage to an integer.
                    $deductionPercentage = (int)$deductionPercentage;

                    // Get a deduction multiplier. 
                    $deductionMultiplier = $deductionPercentage / 100;

                    // Get the estimated value of the car from the $data variable. That variable is either set from cahe if data on the car has been saved there.
                    // If the data is not saved in the cache, the variable is set from the response from Wayke.
                    $estimatedValueFomWayke = $data['price']['avg'];

                    // Get how much the deduction should be based on the prediction value from Wayke times the deduction percentage.
                    $prelDeductionValue = $deductionMultiplier * $estimatedValueFomWayke;

                    // Set variables based on the info from the deduction related to the rule.

                    // The max and min values of the car that the deduction is applicable for.
                    $deductionMinValueInterval = $data1[0]['minValueInterval'];
                    $deductionMaxValueInterval = $data1[0]['maxValueInterval'];

                    // echo "\n$deductionMinValueInterval";
                    // echo "\n$deductionMaxValueInterval";

                    // The max and min values of the deduction that is applicable for the car.
                    $deductionMinDeduction = $data1[0]['minDeduction'];
                    $deductionMaxDeduction = $data1[0]['maxDeduction'];

                    // echo "\n$deductionMinDeduction";
                    // echo "\n$deductionMaxDeduction";

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    // I need to check if the estimated value of the car is within the interval of the deductions carmin and max values.
                    if(
                        $estimatedValueFomWayke >= $deductionMinValueInterval &&
                        $estimatedValueFomWayke <= $deductionMaxValueInterval
                    ) {

                        // If the estimated value of the car is within the interval of the deduction, I need 
                        // to check if the deduction value is within the interval of the deduction.
                        if($prelDeductionValue >= $deductionMaxDeduction)
                        {
                            $prelDeductionValue = $deductionMaxDeduction;
        
                        } else if ($prelDeductionValue <= $deductionMinDeduction)
                        {                        
                            $prelDeductionValue = $deductionMinDeduction;
                        }

                    }

                    else if (
                        $estimatedValueFomWayke < $deductionMinValueInterval
                    ) {

                        // Continue to check the next deduction.
                        continue;

                    }

                    // If it is not more or less than the min and max it is fine and can remain the same. 
                    
                    // Add the deduction value to the array of deduction values.
                    array_push($deductionValues, $prelDeductionValue);

                }

                // Sort the deduction values to get the highest value.
                // If the lowest deduction should be used this can be solved by using sort($deductionValues);
                rsort($deductionValues);      

                // I want to assign the the first (the highest) deduction value to the deduction of the rule. 
                $ruleDeduction = $deductionValues[0];

                // Then I need to add the deduction value from the rule to the deduction value from the condition.
                $totalDeduction = $conditionDeduction + $ruleDeduction;

                // Then I need to subtract the total deduction value from the prediction value from Wayke.
                $finalValuation = $estimatedValueFomWayke - $totalDeduction;

                // I need to check if the car is classed as a contender or not. If it is a non-contender I need to return an error.
                if (
                    $ruleIsContender === false
                ) {
                    return response()->json(['error' => 'The car is classed as a non-contender', 'regNr' => $regNo], 200);
                }

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                // Then I need to return the final valuation to the customer.
                return response()->json(['regNr' => $regNo, 'initialvaluation' => $estimatedValueFomWayke, 'finalValuation' => $finalValuation], 200);

                }

            }

            else {

                // If the rules constraints does not match the car, I need to check the next rule. 
                continue;

            }

        }

        return response()->json(['error' => 'No rules match the car'], 200);

        }


}
























