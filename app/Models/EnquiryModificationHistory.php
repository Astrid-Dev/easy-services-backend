<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryModificationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'author',
        'code',
        'user_intervention_date',
        'user_price',
        'provider_intervention_date',
        'provider_price',
        'final_intervention_date',
        'final_price',
        'state',
        'service_id',
        'user_id',
        'service_provider_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service_provider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
