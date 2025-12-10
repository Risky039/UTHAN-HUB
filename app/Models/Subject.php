<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Subject extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['name', 'school_id','tenant_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher')->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'subjectId');
    }
}
