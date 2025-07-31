<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassroomRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // El Route Model Binding nos da el objeto Classroom en la ruta.
        $classroomId = $this->route('classroom')->id;

        return [
            // La regla 'unique' debe ignorar el registro actual que estamos actualizando.
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classrooms')->ignore($classroomId)
            ],
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1|max:999',
        ];
    }
}
