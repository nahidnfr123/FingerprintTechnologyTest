<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
//            'status' => 'required|string|in:todo,doing,done',
            'due_date' => 'nullable|after_or_equal:today',
            'assigned_to' => 'nullable|array',
            'assigned_to.*' => 'nullable|exists:users,id',
        ];
    }
}
