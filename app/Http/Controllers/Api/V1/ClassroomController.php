<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreClassroomRequest;
use App\Http\Requests\Api\V1\UpdateClassroomRequest;
use App\Http\Resources\Api\V1\ClassroomResource;
use App\Models\Classroom;
use App\Services\Api\V1\ClassroomService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class ClassroomController extends Controller
{
    protected $classroomService;
    public function __construct(ClassroomService $classroomService)
    {
        $this->classroomService = $classroomService;
    }
    public function index(Request $request)
    {
        $classrooms = $this->classroomService->getPaginated($request);
        return ClassroomResource::collection($classrooms);
    }
    public function store(StoreClassroomRequest $request)
    {
        $classroom = $this->classroomService->create($request->validated());
        return (new ClassroomResource($classroom))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    public function show(Classroom $classroom)
    {
        return new ClassroomResource($classroom);
    }
    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        $updatedClassroom = $this->classroomService->update($classroom, $request->validated());
        return new ClassroomResource($updatedClassroom);
    }
    public function destroy(Classroom $classroom)
    {
        $this->classroomService->delete($classroom);
        return response()->noContent();
    }
}
