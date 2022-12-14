<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'service_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
