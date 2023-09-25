<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $name
 * @property string $location
 * @property string $gender all, male, female
 * @property int $min expected salary min
 * @property int $max expected salary max
 * @property int $perPage
 * @property int $page
 */
class CandidateSearchRequest extends FormRequest {
    public function rules(): array {
        return [
            'name' => 'sometimes|string|nullable',
            'location' => 'sometimes|string|nullable',
            'gender' => 'sometimes|in:all,male,female|nullable',
            'min' => 'sometimes|int|nullable',
            'max' => 'sometimes|int|nullable',
            'perPage' => 'sometimes|int|nullable',
            'page' => 'sometimes|int|nullable',
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
