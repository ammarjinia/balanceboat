<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRecurringRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'recurring_type' => 'required|in:Daily,Weekly,Monthly,Yearly',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'separation_count' => 'required|integer|min:1',
            'max_occurrences' => 'nullable|integer|min:1',
            'day_of_week' => 'nullable|required_if:recurring_type,Weekly|array',
            'day_of_week.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'day_of_month' => 'nullable|required_if:recurring_type,Monthly|integer|min:1|max:31',
            'month_of_year' => 'nullable|required_if:recurring_type,Yearly|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'recurring_type.required' => 'Recurrence type is required',
            'start_date.required' => 'Start date is required',
            'end_date.after' => 'End date must be after start date',
            'day_of_week.required_if' => 'At least one day of week must be selected',
            'day_of_month.required_if' => 'Day of month is required for monthly recurrence',
            'month_of_year.required_if' => 'Date is required for yearly recurrence',
        ];
    }
}
