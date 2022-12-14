<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_id',
        'organization_id',
        'reason',
        'data',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }
    
    public function organization()
    {
        return $this->belongsTo(ServiceProvider::class);
    }
}
