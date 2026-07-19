<?php

namespace App\Http\Requests\Country;

use App\Http\Requests\BaseRequest;

/**
 * Validasi pembaruan data negara yang sudah ada.
 */
class UpdateCountryRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // $this->merge([]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['name' => 'sometimes|required|string|max:255|unique:countries,name,' . $this->route('country'), 'iso_code' => 'sometimes|required|string|size:2|unique:countries,iso_code,' . $this->route('country'), 'risk_score' => 'nullable|numeric|between:0,100', 'is_active' => 'boolean'];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            // 'email' => 'alamat email',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // 'email.required' => 'Email wajib diisi.',
        ];
    }

    /**
     * Handle actions after validation passes.
     */
    protected function passedValidation(): void
    {
        // 
    }
}