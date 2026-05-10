<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectStationaryRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by the policy
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'rejection_reason.required' => 'Rejection reason is required.',
            'rejection_reason.max' => 'Rejection reason cannot exceed 1000 characters.',
        ];
    }
}
