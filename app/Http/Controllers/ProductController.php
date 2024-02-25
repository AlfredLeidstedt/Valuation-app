<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Product;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Spatie\QueryBuilder\QueryBuilder;


// THIS IS A CLASS CREATED THROUGH ANOTHER TUTORIAL



class ProductController extends Controller
{
    

    // THIS is the standard endpoint.
    public function index(Request $request)
    {

        //THIS method (QueryBuilder) is used to filter by name and is available since we imported a package from 
        // github and use ut in the imports. 
        $products = QueryBuilder::for(Product::class)

        // This is defining which filter is allowed
        ->allowedFilters('name')

        // This method is defining what property the products should be sorted by. 
        ->defaultSort('name')

        //This method is chained to the index endpoint to make it possible for the products to be scrolled through w/ pages. 
        // This is an effective way to save data power. To optimize. 
        ->paginate();

        return new ProductCollection($products);
    }

    
    //THIS ENDPOINT is calling on the Collection class that is returning the product Resource collection to an array.
    
    public function showProductsInList(Request $request)    
    {

        return new ProductCollection(Product::all());

    }


    //This endpoint is working!! It takes the product id and returns the corresponding product
    // It can do this by using the Product Resource class.
    public function show(Request $request, Product $product)
    {

        return new ProductResource($product);

    }



    public function store(StoreProductRequest $request, Product $product)
    {

        // THIS method is calling to the StoreProductRequest Resource that is containing all the rules that are needed 
        // to be able to determine the permission of the requests. 
        $validated = $request->validated();

        // This method then takes the validated class and creates a new instance of it and it is persisted to the database 
        // with the help of the Laravel command: create that comes with the model class. 
        $product = Product::create($validated);

        return new ProductResource($product);

    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        $product -> update($validated);

        return new ProductResource($product);
    }

    public function destroy(Request $request, Product $product)
    {
        $product -> delete();

        return response()->noContent();
    }



}