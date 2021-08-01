<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TokoRequest extends FormRequest
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
        $id = request()->route('id');
        $editRuleName = $id == null ? '' : ',name,' . $id;

        return [
            'name' => ['required', 'max:255', 'unique:tokos' . $editRuleName],
            'address' => ['required'],
            'city' => ['required', 'max:255'],
            'province' => ['required', 'max:255'],
            'toko_photo.*' => ['mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}
