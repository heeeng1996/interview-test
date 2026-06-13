<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'uuid', 'exists:categories,uuid'],
            'name' => ['sometimes', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'quantity' => ['sometimes', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'discount' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'rating' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            'review' => ['sometimes', 'integer', 'min:0'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['uuid', 'exists:suppliers,uuid'],
        ];
    }
}
