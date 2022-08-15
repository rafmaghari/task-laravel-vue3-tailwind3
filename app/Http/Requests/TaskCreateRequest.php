<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
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
            'title' => 'required',
            'description' => 'required',
            'due_date' => ['nullable', 'date'],
            'priority' => ['nullable', 'in:0,1,2,3'],
            'files.*' => ['nullable', 'mimes:mp4,csv,txt,doc,docx,svg,png,jpg']
        ];
    }
}
