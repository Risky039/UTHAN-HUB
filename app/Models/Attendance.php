<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Attendance extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'date', 'present', 'studentId', 'lessonId',
        'school_id', 'classRoomId', 'academicSessionId', 'termId','tenant_id'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'studentId');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lessonId');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'classRoomId');
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
