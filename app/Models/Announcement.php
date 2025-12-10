<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Announcement extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['title', 'description', 'date', 'school_id', 'academicSessionId', 'termId', 'tenant_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class, 'academicSessionId');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'termId');
    }
}
