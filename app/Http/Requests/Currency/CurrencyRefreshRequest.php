<?php

namespace App\Http\Requests\Currency;

use App\Http\Requests\BaseRequest;

/**
 * Validasi sinkronisasi nilai tukar terbaru.
 */
class CurrencyRefreshRequest extends BaseRequest
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
        return ['base_currency' => 'required|string|size:3', 'symbols' => 'nullable|array', 'symbols.*' => 'string|size:3'];
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