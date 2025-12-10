<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ClassRoom extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['name', 'capacity', 'grade_id', 'school_id', 'form_teacher', 'tenant_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'form_teacher');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'classId');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'classId');
    }
}
