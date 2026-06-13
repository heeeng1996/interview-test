<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'company_name' => 'sometimes|nullable|string|max:255',
            'contact_name' => 'sometimes|nullable|string|max:255',
            'contact_title' => 'sometimes|nullable|string|max:255',
            'contact_number' => 'sometimes|nullable|string|max:20',
            'contact_email' => 'sometimes|nullable|email|max:255',
            'address' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|nullable|string|max:100',
            'postcode' => 'sometimes|nullable|string|max:20',
            'state' => 'sometimes|nullable|string|max:100',
            'country' => 'sometimes|nullable|string|max:100',
        ];
    }
}
