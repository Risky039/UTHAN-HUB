<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Section extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'name', 'school_id', 'tenant_id'
    ];


}
