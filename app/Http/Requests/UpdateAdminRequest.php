<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required|max:180',
            'last_name' => 'required|max:180',
            'email' => 'required|email:filter|unique:users,email,'.$this->route('user')->id,
            'phone' => 'nullable',
            'password' => 'nullable|min:6|same:cpassword',
            'cpassword' => 'nullable|min:6',
            'profile' => 'nullable|mimes:jpeg,jpg,png',
        ];

        return $rules;
    }
}
