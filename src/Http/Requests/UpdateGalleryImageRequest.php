<?php

namespace Taki47\Gallery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGalleryImageRequest extends FormRequest
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
            "caption" => ["nullable", "string", "max:255"],
            "alt" => ["nullable", "string", "max:255"],
        ];
    }
}
