<?php
namespace App\Http\Requests\Api\V1;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $subjectId = $this->route('subject')->id;
        return [
            'name' => 'required|string|max:255',
            'acronym' => ['required', 'string', 'max:20', Rule::unique('subjects')->ignore($subjectId)],
        ];
    }
}
