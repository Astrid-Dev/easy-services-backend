<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\ServiceProvider;
use App\Models\ServiceProviderApplication;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationEmployeeController extends Controller
{
    public function search_for_user(Request $request){
        $users_list = [];

        if($request->email && $request->email !== ''){
            $users_list = User::where('email', 'LIKE', "%{$request->email}%")->limit(5)->get();
        }
        else if($request->username && $request->username !== ''){
            $users_list = User::where('username', 'LIKE', "%{$request->username}%")->limit(5)->get();
        }

        foreach($users_list as $user){
            $user->is_free = ($user->role === 'SIMPLE_USER');
            if($user->role === 'PROVIDER'){
                $provider = $user->load('provider');
                $user->is_free = ($provider->organization_id === null);
                $user->service_provider_id = $provider->id;
            }
        }

        return Response(json_encode($users_list));
    }

    public function register_new_employee(Request $request){
        $validator = Validator::make($request->all(), [
            'organization_id' => 'required|integer|exists:organizations,id',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = null;
        $service_provider = null;

        if($request->service_provider_id){
            $service_provider = ServiceProvider::findOrFail($request->service_provider_id);
            $user = User::findOrFail($service_provider->user_id);

            $service_provider->organization_id = $request->organization_id;

            $service_provider->save();
        }
        else if($request->user){
            $user_data = $request->user;

            $validator2 = Validator::make($user_data, [
                'names' => 'sometimes|string|between:3,80|unique:users',
                'phone_number' => 'sometimes|string|between:9,18|unique:users',
                'username' => 'sometimes|string|between:2,20|unique:users',
                'email' => 'required|string|email|max:100|unique:users',
            ]);
            if($validator2->fails()){
                return response()->json($validator2->errors()->toJson(), 400);
            }
            $user = User::create(array_merge(
                        $validator2->validated(),
                        ['password' => bcrypt('123456')]
                    ));

            $service_provider = ServiceProvider::create([
                'user_id' => $user->id,
                'organization_id' => $request->organization_id
            ]);

            $user->role = 'PROVIDER';
            $user->save();
        }
        else{
            abort(400);
        }

        ServiceProviderApplication::where('service_provider_id', $service_provider->id)->delete();

        if($request->services){
            foreach($request->services as $service){
                ServiceProviderApplication::create([
                    "service_id" => $service,
                    "service_provider_id" => $service_provider->id
                ]);
            }
        }

        $service_provider_applications = ServiceProviderApplication::where("service_provider_id", $service_provider->id)->get();

        return Response(json_encode([
            'message' => 'Employee created successfully !',
            'employee' => $service_provider,
            'user' => $user,
            'applications' => $service_provider_applications
        ]), 201);
    }

    public function search_some_providers_for_request($organization_id, Request $request){
        $providers = ServiceProvider::where('organization_id', $organization_id)->limit(5);
        $enquiry_provider = null;
        if($request->enquiry_id){
            $enquiry = Enquiry::findOrFail($request->enquiry_id);

            $providers = $providers->where('id', '!=', $enquiry->service_provider_id);

            $enquiry_provider = $enquiry->service_provider_id ? ServiceProvider::findOrFail($enquiry->service_provider_id) : null;
            if($enquiry_provider && $enquiry_provider->organization_id !== intval($organization_id)){
                abort(403, "This organization ($request->organization_id)  doesn't have rights to edit the given enquiry ($request->enquiry_id)");
            }
        }

        $providers = $providers->get();

        foreach($providers as $provider){
            $actives_provider_enquiries = Enquiry::where('service_provider_id', $provider->id)
                                                    ->whereIn('state', [1, 2, 3])->get();
            
                                                    
            array_filter(iterator_to_array($actives_provider_enquiries), function($enq) use($enquiry){
                $prov_intervention_date = ($enq->state === 3) ? ($enq->final_intervention_date) :
                                    (($enq->state === 2) ? $enq->provider_intervention_date : $enq->user_intervention_date);
                $incoming_intervention_date = ($enquiry->state === 3) ? ($enquiry->final_intervention_date) :
                                    (($enquiry->state === 2) ? $enquiry->provider_intervention_date : $enquiry->user_intervention_date);
                $time1 = strtotime($prov_intervention_date);
                $time2 = strtotime($incoming_intervention_date);
                $now = new DateTime();
                $now = $now->getTimestamp();

                return ((($time1 + (1000 * 60 * 60)) < $time2) || (($time1 - (1000 * 60 * 60)) > $time2)) &&
                         (($time1 >= $now - (1000 * 60 * 60 * 12)));
            });

            $provider->user = User::findOrFail($provider->user_id);
            $provider->enquiries = $actives_provider_enquiries;
        }

        return Response(json_encode($providers));
    }
}
