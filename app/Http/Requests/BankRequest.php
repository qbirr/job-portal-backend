<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankRequest extends FormRequest {
    public function rules(): array {
        return [
            'name' => ['required'],
            'acc_no' => ['required'],
            'acc_name' => ['required'],
            'swift_code' => ['required'],
            'notes' => ['nullable'],
            'is_active' => ['required', 'bool'],
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
