<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListTaskRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sorting' => ['array'],
            'sorting.*.id' => ['in:title,status,created_at'],
            'sorting.*.desc' => ['boolean'],
            'filters' => ['array'],
            'filters.*.id' => ['in:title,status'],
            'filters.*.value' => ['string'],
        ];
    }
}
