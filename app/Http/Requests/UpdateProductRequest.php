<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'sku' => ['sometimes', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($productId)],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'brand' => ['sometimes', 'string', 'max:255'],
            'status' => ['sometimes', 'boolean'],
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],

            'main_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],

            'gallery_images' => ['nullable', 'array', 'max:10'],
            'gallery_images.*' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],

            'delete_gallery_images' => ['nullable', 'array'],
            'delete_gallery_images.*' => ['integer', 'exists:product_images,id'],

            'files' => ['nullable', 'array', 'max:5'],
            'files.*' => ['file', 'mimes:pdf,doc,docx,txt', 'max:10240'],

            'delete_files' => ['nullable', 'array'],
            'delete_files.*' => ['integer', 'exists:product_files,id'],

            'attributes' => ['nullable', 'array'],
            'attributes.*.name' => ['required', 'string', 'max:255'],
            'attributes.*.value' => ['required', 'string', 'max:255'],

            'title' => ['sometimes', 'array'],
            'title.en' => ['required_with:title', 'string', 'max:255'],
            'title.ar' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'locale' => app()->getLocale(),
            'message' => 'Validation Failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
