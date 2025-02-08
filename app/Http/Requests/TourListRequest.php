<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourListRequest extends FormRequest
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
            'priceFrom' => ['nullable', 'numeric'],
            'priceTo' => ['nullable', 'numeric'],
            'dateFrom' => ['nullable', 'date'],
            'dateTo' => ['nullable', 'date'],
            'sortBy' => ['nullable', Rule::in(['price'])],
            'sortOrder' => ['nullable', Rule::in(['asc', 'desc'])],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'sortBy.in' => "The 'sortBy' parameter accepts only 'price' value.",
            'sortOrder.in' => "The 'sortOrder' parameter accepts only 'asc' or 'desc' values.",
        ];
    }
}
