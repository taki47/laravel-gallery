<?php

namespace Taki47\Gallery\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryImageRequest extends FormRequest
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
            "image" => ["required", "image", "mimes:jpg,jpeg,png,webp", "max:5120"],
            "caption" => ["nullable", "string", "max:255"],
            "alt" => ["nullable", "string", "max:255"],
        ];
    }
}
