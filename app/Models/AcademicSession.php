<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class AcademicSession extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['name', 'school_id', 'start_date', 'end_date', 'status', 'tenant_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'academicSessionId');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'academicSessionId');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'academicSessionId');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'academicSessionId');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'academicSessionId');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'academicSessionId');
    }

}
