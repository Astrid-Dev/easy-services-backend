<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use App\Models\ServiceProviderApplication;
use App\Http\Requests\StoreServiceProviderRequest;
use App\Http\Requests\UpdateServiceProviderRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services_providers_list = [];
        $services_providers_list = isset($request->organization_id) ? ServiceProvider::where('organization_id', $request->organization_id)
                                                : ServiceProvider::query();

        if(isset($request->provider_name)){
            $services_providers_list = $services_providers_list->joinRelation('user', function ($join) {
                global $request;
                $join->where('users.names', 'LIKE', "%$request->provider_name%");
            });
        }

        $services_providers_list = $services_providers_list->paginate();
        foreach($services_providers_list as $provider){
            $provider->user = $provider->load('user');
            $provider->applications = $provider->load('applications');
        }

        return Response(json_encode($services_providers_list));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreServiceProviderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceProviderRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|unique:service_providers',
        ]);

        $user = User::findOrFail($request->user_id);
        if($user->role !== 'SIMPLE_USER'){
            abort(403);
        }

        $service_provider = ServiceProvider::create(array_merge(
            $validator->validated()
        ));
        
        $user->role = 'PROVIDER';
        $user->save();

        if($request->services){
            $services = explode(',', $request->services);

            foreach($services as $service){
                ServiceProviderApplication::create([
                    "service_id" => $service,
                    "service_provider_id" => $service_provider->id
                ]);
            }
        }

        $service_provider_applications = ServiceProviderApplication::where("service_provider_id", $service_provider->id)->get();

        return Response(json_encode([
            'message' => 'Service provider created successfully !',
            'provider' => $service_provider,
            'user' => $user,
            'applications' => $service_provider_applications
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceProvider  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_service_provider = ServiceProvider::findOrFail($id);
        $searched_service_provider->rating = $searched_service_provider->rating();
        $searched_service_provider->total_profit = $searched_service_provider->total_profit();
        $searched_service_provider->statistics = $searched_service_provider->statistics();

        return Response(json_encode($searched_service_provider));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceProvider  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceProvider $serviceProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateServiceProviderRequest  $request
     * @param  \App\Models\ServiceProvider  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceProviderRequest $request, $id)
    {
        // $searched_service_provider = ServiceProvider::findOrFail($id);

        // $request->validate([
        //     'username' => 'required|string|between:3,20|unique:service_providers',
        //     'email' => 'required|email|max:60',
        // ]);

        // $searched_service_provider->username = $request->username;
        // $searched_service_provider->email = $request->email;

        // $searched_service_provider->save();

        // return Response(json_encode([
        //     'message' => 'Service provider updated successfully !',
        //     'service_provider' => $searched_service_provider
        // ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceProvider  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_service_provider = ServiceProvider::findOrFail($id);

        $searched_service_provider->delete();

        return Response(json_encode([
            'message' => 'Service provider deleted successfully !'
        ]));
    }
}
