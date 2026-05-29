<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePricingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pricing_type' => 'required|in:duration,seasonal,occupancy,promotional',
            'duration' => 'required_if:pricing_type,duration|integer|min:1',
            'price' => 'required|numeric|min:0',
            'promo_price' => 'nullable|numeric|min:0|lt:price',
            'start_date' => 'required_if:pricing_type,seasonal,occupancy|date',
            'end_date' => 'required_if:pricing_type,seasonal,occupancy|date|after:start_date',
            'accommodation_id' => 'nullable|integer|exists:accomodations,id',
            'min_occupancy' => 'nullable|integer|min:1',
            'max_occupancy' => 'nullable|integer|min:1|gt:min_occupancy',
        ];
    }

    public function messages(): array
    {
        return [
            'promo_price.lt' => 'Promotional price must be less than regular price.',
            'end_date.after' => 'End date must be after start date.',
        ];
    }
}
