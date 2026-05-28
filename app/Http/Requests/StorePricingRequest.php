<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePricingRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'pricing_type' => 'required|in:duration,seasonal,occupancy,promotional',
            'duration' => 'required_if:pricing_type,duration|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'promo_price' => 'nullable|numeric|min:0|lt:price',
            'currency' => 'required|string|size:3',
            'start_date' => 'required_if:pricing_type,seasonal,occupancy|date',
            'end_date' => 'required_if:pricing_type,seasonal,occupancy|date|after:start_date',
            'accommodation_id' => 'required_if:pricing_type,occupancy|integer|exists:experience_accomodations,id',
            'min_occupancy' => 'nullable|integer|min:1',
            'max_occupancy' => 'nullable|integer|min:1',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'price.required' => 'Price is required',
            'price.min' => 'Price must be greater than 0',
            'promo_price.lt' => 'Promo price must be less than regular price',
            'end_date.after' => 'End date must be after start date',
            'duration.required_if' => 'Duration is required for duration-based pricing',
            'accommodation_id.required_if' => 'Accommodation is required for occupancy pricing',
        ];
    }
}
