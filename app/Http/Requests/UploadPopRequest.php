<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPopRequest extends FormRequest {
    public function rules(): array {
        return [
            'file' => 'required|file'
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
