<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'email_address' => 'nullable|email',
            'contact_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'website' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'gst_number' => 'nullable|regex:/^[0-9A-Z]{15}$/',
            'pan_number' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'billing_address' => 'nullable|string',
            'account_holder_name' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|regex:/^[0-9]{8,17}$/',
            'ifsc_code' => 'nullable|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'preferred_payout_cycle' => 'nullable|in:weekly,bi-weekly,monthly',
            'upi_id' => 'nullable|regex:/^[a-zA-Z0-9.-]{3,}@[a-zA-Z]{3,}$/',
        ];
    }

    public function messages(): array
    {
        return [
            'gst_number.regex' => 'GST number must be 15 alphanumeric characters.',
            'pan_number.regex' => 'PAN number format is invalid.',
            'account_number.regex' => 'Account number must be 8-17 digits.',
            'ifsc_code.regex' => 'IFSC code format is invalid.',
            'upi_id.regex' => 'UPI ID format is invalid.',
        ];
    }
}
