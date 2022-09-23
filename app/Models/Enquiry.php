<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'latitude',
        'longitude',
        'creation_date',
        'intervention_date',
        'state',
        'enquiry_type_id',
        'habitation_id',
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

    public function habitation()
    {
        return $this->belongsTo(Habitation::class);
    }

    public function EnquiryType()
    {
        return $this->belongsTo(EnquiryType::class);
    }
}
