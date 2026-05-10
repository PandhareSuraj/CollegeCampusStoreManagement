<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceiveItemsRequest extends FormRequest
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
            'received_date' => ['required', 'date', 'before_or_equal:today'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.received_quantity' => ['required', 'integer', 'min:0'],
            'items.*.condition_notes' => ['nullable', 'string', 'max:500'],
            'received_by' => ['required', 'string', 'max:255'],
            'receipt_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'received_date.required' => 'Received date is required.',
            'received_date.date' => 'Received date must be a valid date.',
            'received_date.before_or_equal' => 'Received date cannot be in the future.',
            'items.required' => 'At least one item must be received.',
            'items.min' => 'At least one item must be received.',
            'items.*.product_id.required' => 'Each item must have a product selected.',
            'items.*.product_id.exists' => 'One or more selected products are invalid.',
            'items.*.received_quantity.required' => 'Received quantity is required for each item.',
            'items.*.received_quantity.integer' => 'Received quantity must be a whole number.',
            'items.*.received_quantity.min' => 'Received quantity cannot be negative.',
            'received_by.required' => 'Recipient name is required.',
            'received_by.max' => 'Recipient name cannot exceed 255 characters.',
            'receipt_notes.max' => 'Receipt notes cannot exceed 1000 characters.',
        ];
    }
}
