<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $q
 * @property string $featured
 * @property int $status
 * @property int $perPage
 * @property int $page
 */
class EmployerJobSearchRequest extends FormRequest {
    public function rules(): array {
        return [
            'q' => 'sometimes|string|nullable',
            'featured' => 'sometimes|in:yes,no|nullable',
            'status' => 'sometimes|in:0,1,2,3|nullable',
            'perPage' => 'sometimes|int|nullable',
            'page' => 'sometimes|int|nullable',
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
