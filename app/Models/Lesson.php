<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Lesson extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name', 'day', 'startTime', 'endTime', 'subjectId',
        'classId', 'teacherId', 'school_id','tenant_id'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subjectId');
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'classId');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacherId');
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'lessonId');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'lessonId');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'lessonId');
    }
}
