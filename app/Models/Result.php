<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Result extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'score', 'grade', 'remarks', 'examId', 'assignmentId',
        'studentId', 'academicSessionId', 'termId', 'school_id','tenant_id'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentId');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'examId');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignmentId');
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academicSessionId');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'termId');
    }
}
