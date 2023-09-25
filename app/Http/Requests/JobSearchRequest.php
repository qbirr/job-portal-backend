<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $title
 * @property string $location
 * @property int $perPage
 * @property int $page
 * @property int $job_type_id
 * @property int $no_preference
 * @property int $skill_id
 * @property mixed $company_id
 * @property mixed $experience
 */
class JobSearchRequest extends FormRequest {
    public function rules(): array {
        return [
            'title' => 'sometimes|string|nullable',
            'location' => 'sometimes|string|nullable',
            'job_type_id' => 'sometimes|int|nullable',
            'job_category_id' => 'sometimes|int|nullable',
            'salary_from' => 'sometimes|int|nullable',
            'salary_to' => 'sometimes|int|nullable',
            'career_level_id' => 'sometimes|int|nullable',
            'functional_area_id' => 'sometimes|int|nullable',
            'no_preference' => 'sometimes|int|nullable',
            'skill_id' => 'sometimes|int|nullable',
            'company_id' => 'sometimes|int|nullable',
            'experience' => 'sometimes|int|nullable',
            'featuredJob' => 'sometimes|bool|nullable',
            'perPage' => 'sometimes|int|nullable',
            'page' => 'sometimes|int|nullable',
        ];
    }

    public function authorize(): bool {
        return true;
    }
}
