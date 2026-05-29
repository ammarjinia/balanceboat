<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRecurringRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recurring_type' => 'required|in:Daily,Weekly,Monthly,Yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'separation_count' => 'nullable|integer|min:1',
            'max_num_of_occurrances' => 'nullable|integer|min:1',
            'day_of_week' => 'nullable|array',
            'day_of_week.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'week_of_month' => 'nullable|integer|min:1|max:5',
            'day_of_month' => 'nullable|integer|min:1|max:31',
            'month_of_year' => 'nullable|date_format:m-d',
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after' => 'End date must be after start date.',
        ];
    }
}
