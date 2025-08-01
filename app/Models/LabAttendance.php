<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabAttendance extends Model
{
    use HasFactory;
    protected $fillable = ['lab_session_id', 'student_id', 'pc_number', 'student_signature'];

    public function labSession() { return $this->belongsTo(LabSession::class); }
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
}
