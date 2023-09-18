<?php

namespace App\Http\Requests;

use App\Models\Inquiry;
use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
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
        $rules = Inquiry::$rules;
        if (getSettingValue('enable_google_recaptcha')) {
            $rules['g-recaptcha-response'] = 'required';
        }

        return $rules;
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'You must verify google recaptcha.',
        ];
    }
}
