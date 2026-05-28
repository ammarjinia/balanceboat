<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccommodationRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'about' => 'nullable|string|max:1000',
            'price_per_night_per_guest' => 'required|numeric|min:0.01',
            'currency' => 'required|in:INR,USD,EUR,GBP',
            'max_guest_in_room' => 'required|integer|min:1|max:20',
            'accommodation_default' => 'boolean',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Accommodation title is required',
            'price_per_night_per_guest.required' => 'Price per night is required',
            'price_per_night_per_guest.min' => 'Price must be greater than 0',
            'max_guest_in_room.required' => 'Maximum guests is required',
            'max_guest_in_room.min' => 'At least 1 guest must be allowed',
            'gallery_images.*.image' => 'Each file must be a valid image',
            'gallery_images.*.max' => 'Each image must not exceed 2MB',
        ];
    }
}
