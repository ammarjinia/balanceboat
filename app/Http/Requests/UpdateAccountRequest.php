<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'center_name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:255',
            'year_of_foundation' => 'nullable|integer|min:1800|max:' . date('Y'),
            'email_address' => 'required|email|max:255',
            'phone_number' => 'required|string|regex:/^[0-9\+\-\s]{10,}$/',
            'whatsapp_number' => 'nullable|string|regex:/^[0-9\+\-\s]{10,}$/',
            'website' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'billing_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'gst_number' => 'nullable|string|regex:/^[A-Z0-9]{15}$/',
            'pan_number' => 'nullable|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'account_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|regex:/^[0-9]{8,17}$/',
            'ifsc_code' => 'nullable|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'upi_id' => 'nullable|string|regex:/^[\w.-]+@[\w.-]+$/',
            'preferred_payout_cycle' => 'nullable|in:weekly,bi-weekly,monthly',
        ];
    }

    public function messages()
    {
        return [
            'center_name.required' => 'Center name is required',
            'contact_person.required' => 'Contact person name is required',
            'email_address.email' => 'Please provide a valid email',
            'phone_number.regex' => 'Please provide a valid phone number',
            'gst_number.regex' => 'Please provide a valid GST number (15 characters)',
            'pan_number.regex' => 'Please provide a valid PAN number',
            'account_number.regex' => 'Please provide a valid bank account number',
            'ifsc_code.regex' => 'Please provide a valid IFSC code',
            'upi_id.regex' => 'Please provide a valid UPI ID',
        ];
    }
}
