<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, BelongsToTenant;

    protected $fillable = ['name', 'email', 'phone', 'password', 'school_id', 'tenant_id'];

    protected $hidden = ['password'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
