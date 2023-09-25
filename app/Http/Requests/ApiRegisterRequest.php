<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ApiRegisterRequest extends FormRequest {
    public function rules(): array {
        return [
            'first_name' => 'required',
            'email' => 'required|email:filter|unique:users',
            'password' => 'required|min:6',
            'type' => 'required|in:1,2',
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
