<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email,|regex:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/',
            'phone' => 'nullable',
            'password' => 'required|min:6|same:cpassword',
            'cpassword' => 'required|min:6',
            'profile' => 'nullable|mimes:jpeg,jpg,png',
        ];

        return $rules;
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'password.same' => 'The password and confirm password must match',
        ];
    }
}
