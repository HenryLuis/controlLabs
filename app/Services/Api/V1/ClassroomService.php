<?php

namespace App\Services\Api\V1;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ClassroomService
{
    /**
     * Obtiene una lista paginada de aulas, permitiendo búsqueda y ordenación.
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getPaginated(Request $request): LengthAwarePaginator
    {
        return Classroom::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy(
                $request->input('sortBy', 'name'),
                $request->input('sortDirection', 'asc')
            )
            ->paginate($request->input('perPage', 10));
    }

    /**
     * Crea una nueva aula.
     *
     * @param array $data
     * @return Classroom
     */
    public function create(array $data): Classroom
    {
        return Classroom::create($data);
    }

    /**
     * Actualiza un aula existente.
     *
     * @param Classroom $classroom
     * @param array $data
     * @return Classroom
     */
    public function update(Classroom $classroom, array $data): Classroom
    {
        $classroom->update($data);
        return $classroom;
    }

    /**
     * Elimina (soft delete) un aula.
     *
     * @param Classroom $classroom
     * @return bool|null
     */
    public function delete(Classroom $classroom): ?bool
    {
        return $classroom->delete();
    }
}
