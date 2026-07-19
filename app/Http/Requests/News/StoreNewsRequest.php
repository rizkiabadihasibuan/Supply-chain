<?php

namespace App\Http\Requests\News;

use App\Http\Requests\BaseRequest;

/**
 * Validasi penyimpanan artikel berita baru secara manual atau dari API.
 */
class StoreNewsRequest extends BaseRequest
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
        return ['title' => 'required|string|max:255', 'url' => 'required|url|unique:news,url', 'source' => 'required|string|max:100', 'published_at' => 'required|date', 'content' => 'required|string', 'sentiment_score' => 'nullable|numeric|between:-1,1', 'country_id' => 'nullable|integer|exists:countries,id'];
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