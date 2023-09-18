<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebRegisterRequest extends FormRequest
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
            'first_name' => 'required',
            'email' => 'required|email:filter|unique:users',
            'password' => 'nullable|same:password_confirmation|min:6',
            'privacyPolicy' => 'required',
        ];

        if (getSettingValue('enable_google_recaptcha')) {
            $rules['g-recaptcha-response'] = 'required';
        }

        return $rules;
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'privacyPolicy.required' => 'You must agreed to Terms and conditions.',
            'g-recaptcha-response.required' => 'You must verify google recaptcha.',
        ];
    }
}
