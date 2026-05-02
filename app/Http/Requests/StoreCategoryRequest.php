<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class StoreCategoryRequest extends FormRequest
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
            'status' => ['sometimes', 'boolean'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
            ],
            'name' => ['nullable', 'array'],
            'name.en' => ['required_with:name', 'string', 'max:255'],
            'name.ar' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'locale' => app()->getLocale(),
            'message' => 'Category Validation Failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
