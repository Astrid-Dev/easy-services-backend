<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'label_en',
        'parent_id',
        'illustration',
    ];

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id', 'id');
    }
}
