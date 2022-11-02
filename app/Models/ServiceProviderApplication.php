<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviderApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider_id',
        'service_id',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }
}
