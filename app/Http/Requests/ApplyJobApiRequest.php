<?php

namespace App\Http\Requests;

use App\Models\JobApplication;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ApplyJobRequest
 */
class ApplyJobApiRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void {
        $expectedSalary = removeCommaFromNumbers($this->request->get('expected_salary'));

        $this->request->set('expected_salary', $expectedSalary);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        return [
            'resume_id' => 'required',
            'expected_salary' => 'required|numeric|min:0|max:9999999999',
            'application_type' => 'required|in:draft,apply'
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array {
        return [
            'resume_id.required' => 'The Resume Field is Required.',
        ];
    }
}
