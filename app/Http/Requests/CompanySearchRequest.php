<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string $location
 * @property string $industry
 * @property bool $isFeatured
 * @property int $perPage
 * @property int $page
 */
class CompanySearchRequest extends FormRequest {

    public function rules(): array {
        return [
            'name' => 'sometimes|string|nullable',
            'location' => 'sometimes|string|nullable',
            'industry' => 'sometimes|string|nullable',
            'isFeatured' => 'sometimes|bool|nullable',
            'perPage' => 'sometimes|int|nullable',
            'page' => 'sometimes|int|nullable',
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
