<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_day' => 'required|integer|min:1',
            'schedule_start_time' => 'required|date_format:H:i',
            'schedule_end_time' => 'required|date_format:H:i|after:schedule_start_time',
            'activity_description' => 'required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_end_time.after' => 'End time must be after start time.',
        ];
    }
}
