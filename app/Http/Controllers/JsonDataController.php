<?php

namespace App\Http\Controllers;

    use App\Models\CarModel;
    use Illuminate\Http\Request;

// THIS IS A CLASS CREATED THROUGH ANOTHER TUTORIAL



class JsonDataController extends Controller
{

    public function storeJsonData (Request $request) 
    { 
    // Your JSON data

    $jsonData = $request->getContent();

    // Decode the JSON data into an array of objects
    $dataArray = json_decode($jsonData, true);

    // Validate the JSON data if needed
    if (!is_array($dataArray)) {
        return response()->json(['error' => 'Invalid JSON data'], 400);
    }

    
        // Store each JSON object as a separate record in the database
        foreach ($dataArray as $data) {
            // Ensure required fields are present in each JSON object
            if (!isset($data['make'], $data['model'], $data['total_count'])) {
                return response()->json(['error' => 'Missing required fields'], 400);
            }
        
            // Create a new record in the database
            CarModel::create([
                'make' => $data['make'],
                'model' => $data['model'],
                // Assuming `total_count` is also a field in your `car_models` table
                'total_count' => $data['total_count']
            ]);
        }

    return response()->json(['message' => 'JSON data stored successfully'], 200);

}

}