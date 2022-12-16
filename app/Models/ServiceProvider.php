<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class ServiceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function applications()
    {
        return $this->hasMany(ServiceProviderApplication::class);
    }

    public function rating(){
        $temp = $this->hasMany(ProviderReviewHistory::class, 'provider_id');
        $rating['users'] = $temp->count();
        $rating['value'] = floatval($temp->avg('review'));
        return $rating;
    }
    
    public function total_profit(){
        $temp = $this->hasMany(Enquiry::class)->where('state', 4);
        return floatval($temp->sum('final_price'));
    }

}
