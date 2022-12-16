<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\EnquiryModificationHistory;
use App\Models\Organization;
use App\Models\ProviderReviewHistory;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use stdClass;

class DashoardController extends Controller
{
    //

    public function organization_dashboard($organization_id, Request $request){
        $organization = Organization::findOrFail($organization_id);
        $organization_providers = ServiceProvider::where('organization_id', $organization->id)->get();
        foreach($organization_providers as $prov){
            $prov->rating = $prov->rating();
            $prov->total_profit = $prov->total_profit();
        }
        $providers_ids = [];
        foreach($organization_providers as $temp){
            $providers_ids[] = $temp->id;
        }
        $involved_enquiries = Enquiry::whereIn('service_provider_id', $providers_ids);
        $involved_enquiries_states = EnquiryModificationHistory::select('state')->distinct('enquiry_id')->get();
        foreach($involved_enquiries_states as $stat){
            $stat->total = EnquiryModificationHistory::where('state', $stat->state)->where('service_provider_id', $request->service_provider_id)->count();
            if($stat->state === 0)
            {
                $stat->total1 = EnquiryModificationHistory::where('state', $stat->state)->where('service_provider_id', $request->service_provider_id)->where('author', 'user')->count();
                $stat->total2 = EnquiryModificationHistory::where('state', $stat->state)->where('service_provider_id', $request->service_provider_id)->where('author', 'provider')->count();
            }
        }

        $providers_reviews = ProviderReviewHistory::whereIn('provider_id', $providers_ids)->groupBy('provider_id')->avg('review');

        return Response(json_encode([
            'rating' => $organization->rating(),
            'total_profit' => $organization->total_profit(),
            'approved_requests' => $organization->approved_enquiries()->count(),
            'cancelled_requests' => $organization->cancelled_enquiries()->count(),
            'solved_requests' => $organization->solved_enquiries()->count(),
            'bests_employees' => $organization->bests_employees(),
        ]));
    }
}
