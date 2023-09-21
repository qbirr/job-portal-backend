<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class CreateCompanyRequest extends FormRequest {
    /**
     * @throws ValidationException
     */
    public function prepareForValidation(): void {
        $employerDetails = trim(request()->get('details'));
        if (empty($employerDetails)) {
            throw ValidationException::withMessages([
                'details' => 'Employer Details is required',
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array {
        $rules = Company::$rules;
        $rules['name'] = 'required|max:180';
        $rules['email'] = 'required|email:filter|unique:users,email';
        $rules['password'] = 'required|same:password_confirmation|min:6';
        $rules['phone'] = 'nullable';
        $rules['image'] = 'required|mimes:jpg,jpeg,png';

        return $rules;
    }

    /**
     * @return array|string[]
     */
    public function messages(): array {
        return [
            'submission_notes.string' => 'The submission notes is required.',
        ];
    }
}
