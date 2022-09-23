<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnquiryType extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'label_en',
    ];

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }
}
