<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Assignment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['title', 'score', 'lessonId', 'academicSessionId', 'termId', 'school_id', 'tenant_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lessonId');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'termId');
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academicSessionId');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'assignmentId');
    }
}
