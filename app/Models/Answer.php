<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'enquiry_id',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }
}
