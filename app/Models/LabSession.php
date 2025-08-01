<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabSession extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'classroom_id',
        'subject_id',
        'teacher_id',
        'session_date',
        'start_time',
        'end_time',
        'internal_control_reviewer_id',
        'internal_control_reviewed_at',
        'status', // <-- Añadir
        'closed_at', // <-- Añadir
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'session_date' => 'date',
        'internal_control_reviewed_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'internal_control_reviewer_id');
    }

    public function attendances()
    {
        return $this->hasMany(LabAttendance::class);
    }

    public function observations()
    {
        return $this->hasMany(LabObservation::class)->latest(); // Ordenamos por más recientes
    }
}
