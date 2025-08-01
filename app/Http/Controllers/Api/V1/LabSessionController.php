<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreLabSessionRequest;
use App\Http\Resources\Api\V1\LabSessionResource;
use App\Models\LabSession;
use App\Services\Api\V1\LabSessionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LabSessionController extends Controller
{
    use AuthorizesRequests;

    protected $labSessionService;

    public function __construct(LabSessionService $labSessionService)
    {
        $this->labSessionService = $labSessionService;
    }

    // LISTAR Y MOSTRAR (sin cambios)
    public function index(Request $request)
    {
        $sessions = $this->labSessionService->getPaginated($request);
        return LabSessionResource::collection($sessions);
    }
    public function show(LabSession $labSession)
    {
        return new LabSessionResource($labSession->load(['attendances.student', 'observations.user']));
    }

    // MÉTODO 'STORE' REFACTORIZADO
    public function store(StoreLabSessionRequest $request)
    {
        $labSession = $this->labSessionService->create($request->validated());
        return (new LabSessionResource($labSession))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    // --- NUEVOS MÉTODOS DEL FLUJO ---

    public function addAttendance(Request $request, LabSession $labSession)
    {
        // Autorización: el usuario debe tener permiso para "crear" una sesión para poder registrarse.
        $this->authorize('create-lab-session');

        $validated = $request->validate([
            'pc_number' => 'required|string|max:20',
            'student_signature' => 'nullable|string',
        ]);

        $attendanceData = array_merge($validated, ['student_id' => $request->user()->id]);

        $this->labSessionService->addAttendance($labSession, $attendanceData);

        return response()->json(['message' => 'Asistencia registrada con éxito.'], 201);
    }

    public function addObservation(Request $request, LabSession $labSession)
    {
        $validated = $request->validate([
            'observation' => 'required|string|min:5',
        ]);

        $observationData = array_merge($validated, ['user_id' => $request->user()->id]);

        $this->labSessionService->addObservation($labSession, $observationData);

        return response()->json(['message' => 'Observación añadida con éxito.'], 201);
    }

    public function close(Request $request, LabSession $labSession)
    {
        // Lógica de autorización: solo el docente que creó la sesión puede cerrarla.
        if ($request->user()->id !== $labSession->teacher_id) {
            return response()->json(['message' => 'No autorizado para cerrar esta sesión.'], 403);
        }

        $this->labSessionService->closeSession($labSession);

        return response()->json(['message' => 'Sesión cerrada con éxito.']);
    }

    // --- MÉTODOS EXISTENTES SIN CAMBIOS MAYORES ---

    public function markAsReviewed(Request $request, LabSession $labSession)
    {
        $this->authorize('review-lab-session');
        $reviewedSession = $this->labSessionService->markAsReviewed($labSession, $request->user()->id);
        return new LabSessionResource($reviewedSession);
    }

    public function downloadPdf(LabSession $labSession)
    {
        $this->authorize('download-lab-session-pdf');
        return $this->labSessionService->generatePdf($labSession);
    }
}
