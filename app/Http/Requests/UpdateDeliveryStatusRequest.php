<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by the policy (provider-only)
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
            'delivery_status' => ['required', 'in:Pending,In_Transit,Delivered,Delayed'],
            'estimated_arrival_date' => ['nullable', 'date', 'after_or_equal:today'],
            'delivery_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'delivery_status.required' => 'Delivery status is required.',
            'delivery_status.in' => 'Delivery status must be one of: Pending, In Transit, Delivered, or Delayed.',
            'estimated_arrival_date.date' => 'Estimated arrival date must be a valid date.',
            'estimated_arrival_date.after_or_equal' => 'Estimated arrival date cannot be in the past.',
            'delivery_notes.max' => 'Delivery notes cannot exceed 1000 characters.',
        ];
    }
}
