<?php
namespace App\Services\Api\V1;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
class SubjectService
{
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        return Subject::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('acronym', 'like', "%{$search}%");
            })
            ->orderBy($request->input('sortBy', 'name'), $request->input('sortDirection', 'asc'))
            ->paginate($request->input('perPage', 10));
    }
    public function create(array $data): Subject
    {
        return Subject::create($data);
    }
    public function update(Subject $subject, array $data): Subject
    {
        $subject->update($data);
        return $subject;
    }
    public function delete(Subject $subject): ?bool
    {
        return $subject->delete();
    }
}
