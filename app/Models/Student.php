<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Student extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, BelongsToTenant;

    protected $fillable = [
        'username', 'name', 'surname', 'email', 'phone', 'address',
        'passport', 'bloodType', 'sex', 'birthday', 'guardianName',
        'school_id', 'classId', 'gradeId', 'tenant_id', 'password'
    ];

    protected $hidden = [
        'password',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'classId');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'gradeId');
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'studentId');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'studentId');
    }
}
