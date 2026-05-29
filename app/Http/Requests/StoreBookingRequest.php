<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[0-9\s\+\-\(\)]+$/',
            'arrival_date' => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'guest_count' => 'required|integer|min:1|max:20',
            'accommodation_id' => 'required|integer|exists:experience_accomodations,id',
            'message' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string',
            'billing_city' => 'nullable|string',
            'billing_state' => 'nullable|string',
            'billing_zip' => 'nullable|string',
            'billing_country' => 'nullable|string',
            'agree_terms' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'arrival_date.after_or_equal' => 'Arrival date must be today or in the future.',
            'departure_date.after' => 'Departure date must be after arrival date.',
            'guest_count.min' => 'At least 1 guest is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'agree_terms.required' => 'You must agree to the terms and conditions.',
        ];
    }
}
