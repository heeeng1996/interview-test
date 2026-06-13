<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
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
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_title' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ];
    }
}
