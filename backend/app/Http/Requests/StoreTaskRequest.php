<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:Pending,In Progress,Completed',
            'assigned_to' => 'required|exists:users,id',
            'deadline' => 'nullable|date|after:now',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required',
            'assigned_to.exists' => 'The selected user does not exist',
            'deadline.after' => 'Deadline must be a future date',
            'status.in' => 'Status must be one of: Pending, In Progress, Completed',
        ];
    }
}
