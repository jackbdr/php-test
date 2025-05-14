<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:tasks,name|min:3|max:100',
            'description' => 'required|string|min:10|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name of the task is required.',
            'name.unique' => 'The name of the task has already been taken.',
            'name.min' => 'The name of the task must be at least 3 characters.',
            'name.max' => 'The name of the task may not be greater than 100 characters.',
            'description.required' => 'Please provide a task description',
            'description.min' => 'The task description must be at least 10 characters.',
            'description.max' => 'The task description may not be greater than 5000 characters.',
        ];
    }
}
