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

    public function bests_employees(){
        $providers = $this->employees()->get();
        foreach($providers as $prov){
            $temp = ProviderReviewHistory::where('provider_id', $prov->id);
            $prov->user = $prov->load('user');
            $rating['users'] = $temp->count();
            $rating['value'] = floatval($temp->avg('review'));
            $prov->rating = $rating;
        }

        $bests = $providers->toArray();

        usort($bests, function ($a, $b) {
            return $a->rating->value - $b->rating->value;
        });

        return array_slice($bests, 0, 5);
    }

    public function enquiries(){
        $ids = $this->providers_ids();
        $temp = Enquiry::whereIn('service_provider_id', $ids)->get();
        return $temp;
    }
    
    public function enquiries_histories(){
        $ids = $this->providers_ids();
        $temp = EnquiryModificationHistory::whereIn('service_provider_id', $ids);
        return $temp;
    }
    
    public function approved_enquiries(){
        $ids = $this->providers_ids();
        $temp = $this->enquiries_histories()->where('state', 3)->distinct('service_provider_id');
        return $temp;
    }
    
    public function cancelled_enquiries(){
        $ids = $this->providers_ids();
        $temp = $this->enquiries_histories()->where('state', 0)->distinct('service_provider_id');
        return $temp;
    }
    
    public function solved_enquiries(){
        $ids = $this->providers_ids();
        $temp = $this->enquiries_histories()->where('state', 4)->distinct('service_provider_id');
        return $temp;
    }
    
    public function traded_enquiries(){
        $ids = $this->providers_ids();
        $temp = $this->enquiries_histories()->whereIn('state', [1, 2])->distinct('service_provider_id');
        return $temp;
    }

    public function rating(){
        $ids = $this->providers_ids();
        $temp = ProviderReviewHistory::whereIn('provider_id', $ids);
        $rating['users'] = $temp->count();
        $rating['value'] = floatval($temp->avg('review'));
        return $rating;
    }

    public function total_profit(){
        $temp = $this->enquiries()->where('state', 4);
        return floatval($temp->sum('final_price'));
    }

    public function providers_ids(){
        $temp = $this->employees()->get('id');
        $ids = [];
        foreach ($temp as $t) {
            $ids[] = $t->id;
        };
        return $ids;
    }
}
