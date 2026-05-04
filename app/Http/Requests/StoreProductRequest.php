<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'brand' => ['required', 'string', 'max:255'],

            'main_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp','max:2048'],

            'gallery_images' => ['nullable', 'array', 'max:10'], // overall
            'gallery_images.*' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:2048'], // each image

            'delete_gallery_images' => ['nullable', 'array'],
            'delete_gallery_images.*' => ['integer', 'exists:product_images,id'],

            'files' => ['nullable', 'array', 'max:5'],
            'files.*' => ['file', 'mimes:pdf,doc,docx,txt', 'max:10240'], // 10MB per file
            

            'status' => ['sometimes', 'boolean'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'array'],
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
            'message' => 'Product Validation Failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
