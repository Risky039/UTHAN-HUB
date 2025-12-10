<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Teacher extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, BelongsToTenant;

    protected $fillable = [
        'username', 'name', 'surname', 'email', 'phone', 'address',
        'passport', 'bloodType', 'sex', 'birthday', 'school_id','tenant_id', 'password'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher')
                    ->withTimestamps();
    }

    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class, 'form_teacher');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'teacherId');
    }
}
