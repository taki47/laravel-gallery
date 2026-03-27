<?php

namespace Taki47\Gallery\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGalleryRequest extends FormRequest
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
            "title" => ["required", "string", "max:255", Rule::unique("galleries", "title")->ignore($this->gallery)],
            "description" => ["nullable", "string"],
            "is_public" => ["nullable", "boolean"],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('gallery::gallery.validation.gallery.title_required'),
            'title.unique' => __('gallery::gallery.validation.gallery.title_unique'),
            'title.max' => __('gallery::gallery.validation.gallery.title_max'),
            'event_date.date' => __('gallery::gallery.validation.gallery.event_date_date'),
        ];
    }
}
