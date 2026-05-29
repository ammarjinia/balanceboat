<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRetreatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:experiences,name',
            'price_per_person' => 'required|numeric|min:100',
            'start_date_time' => 'required|date|after_or_equal:today',
            'end_date_time' => 'required|date|after:start_date_time',
            'experience_summary' => 'required|string|min:50',
            'accommodations' => 'required|array|min:1',
            'accommodations.*' => 'integer|exists:accomodations,id',
            'price_per_night' => 'required|numeric|min:0',
            'batch_size' => 'nullable|integer|min:1',
            'what_is_included' => 'nullable|string',
            'what_is_not_included' => 'nullable|string',
            'experience_highlights' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'early_bird_discount' => 'nullable|numeric|min:0|max:100',
            'early_bird_days' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'A retreat with this name already exists.',
            'start_date_time.after_or_equal' => 'Start date must be today or in the future.',
            'end_date_time.after' => 'End date must be after start date.',
            'accommodations.required' => 'Please select at least one accommodation.',
        ];
    }
}
