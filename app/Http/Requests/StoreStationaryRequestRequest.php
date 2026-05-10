<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreStationaryRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Teachers and HODs can create stationary requests
        return $this->user() && in_array($this->user()->role, [
            UserRole::TEACHER->value,
            UserRole::HOD->value,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'department_id' => ['required', 'exists:departments,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'items.*.notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Request title is required.',
            'title.max' => 'Request title cannot exceed 255 characters.',
            'description.required' => 'Request description is required.',
            'description.max' => 'Request description cannot exceed 1000 characters.',
            'department_id.required' => 'Department must be specified.',
            'department_id.exists' => 'The selected department is invalid.',
            'items.required' => 'At least one item must be added to the request.',
            'items.min' => 'The request must contain at least one item.',
            'items.*.product_id.required' => 'Each item must have a product selected.',
            'items.*.product_id.exists' => 'One or more selected products are invalid.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.integer' => 'Quantity must be a whole number.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.quantity.max' => 'Quantity cannot exceed 10,000.',
        ];
    }
}
