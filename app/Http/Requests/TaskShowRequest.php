<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (res)

       return $this->route('task')->user_id == auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
