<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabObservation extends Model
{
    use HasFactory;
    protected $fillable = ['lab_session_id', 'user_id', 'observation'];

    public function labSession() { return $this->belongsTo(LabSession::class); }
    public function user() { return $this->belongsTo(User::class); }
}
