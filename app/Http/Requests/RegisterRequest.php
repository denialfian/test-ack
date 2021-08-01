<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'phone' => ['required', 'max:255', 'unique:users', 'regex:/^(\+62 ((\d{3}([ -]\d{3,})([- ]\d{4,})?)|(\d+)))|(\(\d+\) \d+)|\d{3}( \d+)+|(\d+[ -]\d+)|\d+$/'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'max:255'],
            'confirm_password' => ['required', 'max:255', 'same:password'],
        ];
    }
}
