<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductUnitPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'name' => 'required|string|max:1000',
            'barcode' => 'nullable|string',
            'product_id' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'image' => 'nullable|string',
            'price' => 'numeric',
            'cost' => 'numeric',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
        ];
    }
}
