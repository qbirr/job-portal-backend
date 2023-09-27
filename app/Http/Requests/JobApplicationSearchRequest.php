<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $q
 * @property int $status
 * @property int $perPage
 * @property int|mixed $page
 */
class JobApplicationSearchRequest extends FormRequest {
    public function rules(): array {
        return [
            'q' => 'sometimes|string|nullable',
            'status' => 'sometimes|in:1,2,3,4|nullable',
            'perPage' => 'sometimes|string|nullable',
            'page' => 'sometimes|string|nullable',
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
