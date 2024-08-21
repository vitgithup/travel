<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'image' => 'nullable|string',
            'status' => 'required|boolean',
            'description' => 'nullable|string',
            'product_category_id' => 'required|numeric',
            'base_unit_id' => 'required|numeric',
            'default_unit_id' => 'required|numeric',
            'is_stock' => 'nullable|boolean',
            'stock_order' => 'nullable|boolean',

        ];

        // 'name', 'image', 'status', 'description',  'product_category_id', 'base_unit_id', 'default_unit_id', 'is_stock', 'stock_order'
    }
}
