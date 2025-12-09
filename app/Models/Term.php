<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Term extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name',
        'academic_session_id',
        'school_id',
        'status',
        'start_date',
        'end_date',
        'tenant_id',
        'results_released_at',
        'reset_date'
    ];

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'termId');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'termId');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'termId');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'termId');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'termId');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'termId');
    }
}
