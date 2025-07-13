<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        $user = $this->user();

        return $user->role === 'admin' || $user->id === $task->assigned_to;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        // Admin can update all fields, users can only update status
        if ($user->role === 'admin') {
            return [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string|max:1000',
                'status' => 'sometimes|required|in:Pending,In Progress,Completed',
                'assigned_to' => 'sometimes|required|exists:users,id',
                'deadline' => 'sometimes|nullable|date|after:now',
            ];
        } else {
            return [
                'status' => 'sometimes|required|in:Pending,In Progress,Completed',
            ];
        }
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
