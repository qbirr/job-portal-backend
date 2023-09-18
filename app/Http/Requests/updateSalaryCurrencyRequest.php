<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateSalaryCurrencyRequest extends FormRequest
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
        $rules['currency_name'] = 'required|unique:salary_currencies,currency_name,'.$this->route('currency');
        $rules['currency_icon'] = 'required|unique:salary_currencies,currency_icon,'.$this->route('currency');
        $rules['currency_code'] = 'required|min:3|max:3|unique:salary_currencies,currency_code,'.$this->route('currency');

        return $rules;
    }
}
