<?php

namespace App\Models;

use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasDomains;
    protected $fillable = ['id', 'data'];

    protected $casts = [
        'data' => 'array',
    ];

    public function school()
    {
        return $this->hasOne(School::class, 'id', 'school_id');
    }
   
}
