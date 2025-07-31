<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSubjectRequest;
use App\Http\Requests\Api\V1\UpdateSubjectRequest;
use App\Http\Resources\Api\V1\SubjectResource;
use App\Models\Subject;
use App\Services\Api\V1\SubjectService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class SubjectController extends Controller
{
    protected $subjectService;
    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }
    public function index(Request $request)
    {
        return SubjectResource::collection($this->subjectService->getPaginated($request));
    }
    public function store(StoreSubjectRequest $request)
    {
        $subject = $this->subjectService->create($request->validated());
        return (new SubjectResource($subject))->response()->setStatusCode(Response::HTTP_CREATED);
    }
    public function show(Subject $subject)
    {
        return new SubjectResource($subject);
    }
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        return new SubjectResource($this->subjectService->update($subject, $request->validated()));
    }
    public function destroy(Subject $subject)
    {
        $this->subjectService->delete($subject);
        return response()->noContent();
    }
}
