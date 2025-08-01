<?php
namespace App\Http\Resources\Api\V1;
use App\Http\Resources\Api\V1\SimpleUserResource; // Usamos nuestro resource simple
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class LabSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pc_number' => $this->pc_number,
            'session_date' => $this->session_date->format('Y-m-d'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'observations' => $this->observations,
            // No incluimos la firma en la respuesta de lista por su tamaño
            // 'student_signature' => $this->student_signature,
            'reviewed_at' => $this->internal_control_reviewed_at,
            // --- RELACIONES CARGADAS ---
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'subject' => new SubjectResource($this->whenLoaded('subject')),
            'teacher' => new SimpleUserResource($this->whenLoaded('teacher')),
            'student' => new SimpleUserResource($this->whenLoaded('student')),
            'reviewer' => new SimpleUserResource($this->whenLoaded('reviewer')),
        ];
    }
}
