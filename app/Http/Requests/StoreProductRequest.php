<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'category_id' => ['required', 'uuid', 'exists:categories,uuid'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'discount' => ['required', 'numeric', 'min:0', 'max:100'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'review' => ['required', 'integer', 'min:0'],
            'suppliers' => ['nullable', 'array'],
            'suppliers.*' => ['uuid', 'exists:suppliers,uuid'],
        ];
    }
}
