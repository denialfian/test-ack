<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255'],
            'harga' => ['required', 'numeric'],
            'stok' => ['required', 'numeric'],
            'nomor_rak' => ['required', 'max:255'],
            'toko_id' => ['required', 'numeric'],
            'product_photo.*' => ['mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}
