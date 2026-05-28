<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'schedules' => 'required|array|min:1',
            'schedules.*.day' => 'required|string|max:50',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i',
            'schedules.*.activity' => 'required|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'schedules.required' => 'At least one schedule day is required',
            'schedules.*.activity.required' => 'Activity description is required for each day',
            'schedules.*.start_time.date_format' => 'Start time must be in HH:mm format',
            'schedules.*.end_time.date_format' => 'End time must be in HH:mm format',
        ];
    }
}
