<?php

namespace App\Http\Requests;

use App\Service\UserService;
use Illuminate\Foundation\Http\FormRequest;

class UserProfileUpdateRequest extends FormRequest
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
        $service = new UserService;

        $id = $service->getAuthUser()->id;
        $editRuleEmail = $id == null ? '' : ',email,' . $id;
        $editRulePhone = $id == null ? '' : ',email,' . $id;

        return [
            'name' => ['required', 'max:255'],
            'avatar' => ['mimes:jpg,jpeg,png', 'max:2048'],
            'phone' => ['required', 'max:255', 'unique:users' . $editRuleEmail, 'regex:/^(\+62 ((\d{3}([ -]\d{3,})([- ]\d{4,})?)|(\d+)))|(\(\d+\) \d+)|\d{3}( \d+)+|(\d+[ -]\d+)|\d+$/'],
            'email' => ['required', 'email', 'unique:users' . $editRulePhone, 'max:255'],
        ];
    }
}
