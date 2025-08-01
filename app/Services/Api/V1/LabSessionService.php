<?php

namespace App\Services\Api\V1;

use App\Models\LabSession;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class LabSessionService
{
    /**
     * REFACTORIZADO: Ahora permite filtrar por estado (ej: ?status=open)
     */
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        return LabSession::with(['classroom', 'subject', 'teacher', 'reviewer'])
            ->when($request->input('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate($request->input('perPage', 15));
    }

    /**
     * REFACTORIZADO: Ahora crea solo la cabecera de la sesión.
     */
    public function create(array $data): LabSession
    {
        // El status por defecto es 'open' gracias a la migración.
        return LabSession::create($data);
    }

    /**
     * NUEVO: Añade la asistencia de un estudiante a una sesión abierta.
     */
    public function addAttendance(LabSession $labSession, array $attendanceData): \App\Models\LabAttendance
    {
        // Lógica de negocio CRÍTICA: solo se puede registrar en sesiones abiertas.
        if ($labSession->status !== 'open') {
            throw new Exception('No se puede registrar asistencia en una sesión que ya está cerrada.', 403);
        }

        // Creamos la asistencia asociada a la sesión y al estudiante logueado.
        return $labSession->attendances()->create($attendanceData);
    }

    /**
     * NUEVO: Añade una observación a una sesión.
     */
    public function addObservation(LabSession $labSession, array $observationData): \App\Models\LabObservation
    {
        // Cualquier rol puede añadir observaciones en cualquier momento.
        return $labSession->observations()->create($observationData);
    }

    /**
     * NUEVO: Cierra una sesión de laboratorio.
     */
    public function closeSession(LabSession $labSession): LabSession
    {
        $labSession->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
        return $labSession;
    }

    /**
     * Marca una sesión como revisada por Control Interno.
     */
    public function markAsReviewed(LabSession $labSession, int $reviewerId): LabSession
    {
        // Lógica de negocio: solo se pueden revisar sesiones cerradas.
        if ($labSession->status !== 'closed') {
             throw new Exception('Solo se pueden revisar sesiones que han sido cerradas.', 422);
        }

        $labSession->update([
            'internal_control_reviewer_id' => $reviewerId,
            'internal_control_reviewed_at' => now(),
        ]);
        return $labSession;
    }

    /**
     * Genera un PDF, ahora cargando las nuevas relaciones.
     */
    public function generatePdf(LabSession $labSession)
    {
        // Cargamos todas las relaciones necesarias para el reporte.
        $labSession->load(['classroom', 'subject', 'teacher', 'reviewer', 'attendances.student', 'observations.user']);

        $pdf = Pdf::loadView('pdf.lab_session_report', ['labSession' => $labSession]);

        return $pdf->download('reporte-sesion-' . $labSession->id . '.pdf');
    }
}
