<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskDueDateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'due_date' => ['nullable', 'date', 'after:yesterday'],
        ];
    }

    public function messages()
    {
       return [
           'due_date.after' => 'Due date must be greater equal or greater than today'
       ];
    }
}
