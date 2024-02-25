<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name"=> 'sometimes',
            'brand_id' => 'sometimes',
            'slug' => 'sometimes',
            'sku'=>  'sometimes',
            'description'=>  'sometimes',
            'image'=>  'sometimes',
            'quantity'=>  'sometimes',
            'price'=> 'sometimes',
            'is_visable'=>  'sometimes',
            'is_featured'=>  'sometimes',
            'type'=>  'sometimes',
            'published'=> 'sometimes'
        ];
    }
}
