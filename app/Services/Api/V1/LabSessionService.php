<?php
namespace App\Services\Api\V1;
use App\Models\LabSession;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
class LabSessionService
{
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        // Eager load las relaciones para evitar el problema N+1
        return LabSession::with(['classroom', 'subject', 'teacher', 'student', 'reviewer'])
            ->latest() // Ordenar por más recientes por defecto
            ->paginate($request->input('perPage', 15));
    }

    public function create(array $data): LabSession
    {
        return LabSession::create($data);
    }

    // --- NUEVO MÉTODO ---
    /**
     * Marca una sesión como revisada por Control Interno.
     */
    public function markAsReviewed(LabSession $labSession, $reviewerId): LabSession
    {
        $labSession->update([
            'internal_control_reviewer_id' => $reviewerId,
            'internal_control_reviewed_at' => now(),
        ]);
        return $labSession;
    }
}
