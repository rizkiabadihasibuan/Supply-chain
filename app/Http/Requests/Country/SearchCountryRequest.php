<?php

namespace App\Http\Requests\Country;

use App\Http\Requests\BaseRequest;

/**
 * Validasi parameter pencarian negara.
 */
class SearchCountryRequest extends BaseRequest
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
        return ['query' => 'required|string|min:2', 'limit' => 'nullable|integer|min:1|max:100'];
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