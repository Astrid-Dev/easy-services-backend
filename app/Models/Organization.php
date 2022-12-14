<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'name',
        'logo',
        'phone_number1',
        'phone_number2',
        'email1',
        'email2',
        'website',
        'facebook',
        'instagram',
        'twitter',
        'description',
        'description_en'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(OrganizationApplication::class);
    }

    public function employees()
    {
        return $this->hasMany(ServiceProvider::class);
    }
}
