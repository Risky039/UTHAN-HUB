<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class School extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name', 'address', 'email', 'phone', 'domain', 'logo','tenant_id', 'term_fee'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function academicSessions()
    {
        return $this->hasMany(AcademicSession::class);
    }

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
