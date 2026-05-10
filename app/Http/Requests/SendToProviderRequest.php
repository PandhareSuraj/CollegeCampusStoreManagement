<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendToProviderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by the policy (admin-only)
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
            'vendor_id' => ['required', 'exists:vendors,id'],
            'expected_delivery_date' => ['required', 'date', 'after:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vendor_id.required' => 'Vendor must be specified.',
            'vendor_id.exists' => 'The selected vendor is invalid.',
            'expected_delivery_date.required' => 'Expected delivery date is required.',
            'expected_delivery_date.date' => 'Expected delivery date must be a valid date.',
            'expected_delivery_date.after' => 'Expected delivery date must be in the future.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }
}
