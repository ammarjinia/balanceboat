<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRetreatRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:experiences,name',
            'experience_summary' => 'required|string|min:50|max:2000',
            'experience_category' => 'required|string|max:255',
            'price_per_person' => 'required|numeric|min:100',
            'currency' => 'required|in:INR,USD,EUR,GBP',
            'batch_size' => 'nullable|integer|min:1|max:100',
            'start_date_time' => 'required|date|after_or_equal:today',
            'end_date_time' => 'required|date|after:start_date_time',
            'duration' => 'required|string',
            'is_full_day_event' => 'boolean',
            'is_recurring' => 'boolean',
            'what_is_included' => 'nullable|string|max:2000',
            'what_is_not_included' => 'nullable|string|max:2000',
            'experience_highlights' => 'required|string|min:50|max:2000',
            'cancellation_policy' => 'nullable|string|max:3000',
            'deposit_policy' => 'boolean',
            'deposit_amount' => 'nullable|numeric|min:0',
            'early_bird_discount' => 'nullable|numeric|min:0|max:100',
            'early_bird_days' => 'nullable|integer|min:1',
            'accommodations' => 'required|array|min:1',
            'accommodations.*' => 'integer|exists:experience_accomodations,id',
            'teachers' => 'nullable|array',
            'teachers.*' => 'integer|exists:teachers,id',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:amenities,id',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video_url' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Retreat name is required',
            'name.unique' => 'A retreat with this name already exists',
            'price_per_person.required' => 'Base price is required',
            'price_per_person.min' => 'Price must be at least 100',
            'start_date_time.after_or_equal' => 'Start date cannot be in the past',
            'end_date_time.after' => 'End date must be after start date',
            'experience_summary.min' => 'Summary must be at least 50 characters',
            'accommodations.min' => 'At least one accommodation must be assigned',
            'banner_image.image' => 'Banner must be a valid image',
            'banner_image.max' => 'Banner image must not exceed 2MB',
        ];
    }

    public function validated()
    {
        return array_merge(parent::validated(), [
            'is_draft' => true,
            'is_bookable' => false,
        ]);
    }
}
