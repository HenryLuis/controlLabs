<?php
namespace App\Http\Requests\Api\V1;
use Illuminate\Foundation\Http\FormRequest;
class StoreLabSessionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
            'pc_number' => 'required|string|max:20',
            'session_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'observations' => 'nullable|string',
            // Validamos que la firma sea una cadena (base64)
            'student_signature' => 'nullable|string',
        ];
    }
}
