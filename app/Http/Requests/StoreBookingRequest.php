<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Guests can make bookings
    }

    public function rules()
    {
        return [
            'experience_id' => 'required|integer|exists:experiences,id',
            'accommodation_id' => 'required|integer|exists:experience_accomodations,id',
            'arrival_date' => 'required|date|after_or_equal:today',
            'departure_date' => 'required|date|after:arrival_date',
            'guest_count' => 'required|integer|min:1|max:20',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9\+\-\s]{10,}$/',
            'message' => 'nullable|string|max:1000',
            'coupon_code' => 'nullable|string|max:50',
            'billing_name' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_zip' => 'nullable|string|max:20',
            'billing_country' => 'nullable|string|max:100',
            'billing_email' => 'nullable|email',
            'billing_tel' => 'nullable|string|max:20',
            'agree_terms' => 'required|accepted',
        ];
    }

    public function messages()
    {
        return [
            'experience_id.exists' => 'The selected retreat does not exist',
            'accommodation_id.exists' => 'The selected accommodation does not exist',
            'arrival_date.after_or_equal' => 'Arrival date cannot be in the past',
            'departure_date.after' => 'Departure date must be after arrival date',
            'guest_count.min' => 'At least one guest is required',
            'guest_count.max' => 'Maximum 20 guests per booking',
            'email.email' => 'Please provide a valid email address',
            'phone.regex' => 'Please provide a valid phone number',
            'billing_email.email' => 'Please provide a valid billing email',
            'agree_terms.required' => 'You must agree to the terms and conditions',
        ];
    }
}
