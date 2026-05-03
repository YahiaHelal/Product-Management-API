<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class UpdateCategoryRequest extends FormRequest
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
        $currentId = $this->route('category');
        return [
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                function(string $attribute, mixed $value, $fail) use ($currentId) {
                    $id = is_object($currentId) ? $currentId->id : $currentId;
                    if($value == $id) {
                        $fail("Cannot assign category as parent of itself");
                    }

                    $parentCategory = Category::findOrFail($value);
                    if($parentCategory && !$parentCategory->canBeParentOf($id)) {
                        $fail('Cannot create circular reference in category hierarchy');
                    }
                }
            ],
            'status' => ['sometimes', 'boolean'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'array'],
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
