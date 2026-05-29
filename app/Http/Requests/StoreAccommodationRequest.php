<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccommodationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'accommodation_id' => 'required|integer|exists:accomodations,id',
            'title' => 'required|string|max:255',
            'about' => 'nullable|string',
            'price_per_night_per_guest' => 'required|numeric|min:0',
            'max_guest_in_room' => 'required|integer|min:1',
            'accommodation_default' => 'boolean',
        ];
    }
}
