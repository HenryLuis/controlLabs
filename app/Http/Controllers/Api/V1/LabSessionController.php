<?php

namespace App\Http\Controllers\Api\V1;

// --- Dependencias de Laravel ---
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth; // <-- Importa el Facade Auth
use Illuminate\Support\Facades\Log;

// --- Dependencias de la Aplicación ---
use App\Http\Requests\Api\V1\StoreLabSessionRequest;
use App\Http\Resources\Api\V1\LabSessionResource;
use App\Models\LabSession;
use App\Services\Api\V1\LabSessionService;

class LabSessionController extends Controller
{
    use AuthorizesRequests;

    protected $labSessionService;

    public function __construct(LabSessionService $labSessionService)
    {
        $this->labSessionService = $labSessionService;
    }

    public function index(Request $request)
    {
        Log::info('Accediendo a LabSessionController@index');
        $sessions = $this->labSessionService->getPaginated($request);
        return LabSessionResource::collection($sessions);
    }

    public function store(StoreLabSessionRequest $request)
    {
        Log::info('Iniciando LabSessionController@store.');

        // --- SINTAXIS CORRECTA PARA OBTENER EL USUARIO Y SU ID ---
        Log::info('Usuario autenticado:', ['id' => Auth::id(), 'name' => Auth::user()?->name ?? 'N/A']);

        $validatedData = $request->validated();
        Log::info('Datos validados:', $validatedData);

        Log::info('Llamando a LabSessionService para crear la sesión...');
        $labSession = $this->labSessionService->create($validatedData);
        Log::info('Sesión creada exitosamente.', ['id' => $labSession->id]);

        return (new LabSessionResource($labSession))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(LabSession $labSession)
    {
        Log::info('Accediendo a LabSessionController@show', ['id' => $labSession->id]);
        return new LabSessionResource($labSession->load(['classroom', 'subject', 'teacher', 'student', 'reviewer']));
    }

    public function markAsReviewed(Request $request, LabSession $labSession)
    {
        Log::info('Iniciando LabSessionController@markAsReviewed', ['session_id' => $labSession->id]);
        Log::info('Usuario revisor:', ['id' => $request->user()->id, 'name' => $request->user()->name]);

        $this->authorize('review-lab-session');

        $reviewedSession = $this->labSessionService->markAsReviewed($labSession, $request->user()->id);
        Log::info('Sesión marcada como revisada exitosamente.');

        return new LabSessionResource($reviewedSession);
    }
}
