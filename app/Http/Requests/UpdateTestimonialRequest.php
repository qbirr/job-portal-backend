<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestimonialRequest extends FormRequest
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
            'customer_name' => 'required',
            'customer_image' => 'nullable|mimes:jpeg,jpg,png',
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'customer_image.required' => 'The image field is required.',
        ];
    }
}
