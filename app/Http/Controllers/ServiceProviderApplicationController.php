<?php

namespace App\Http\Controllers;

use App\Models\ServiceProviderApplication;
use App\Http\Requests\StoreServiceProviderApplicationRequest;
use App\Http\Requests\UpdateServiceProviderApplicationRequest;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class ServiceProviderApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $service_provider_applications_list = [];

        if($request->service_provider_id){
            $service_provider_applications_list = ServiceProviderApplication::where("service_provider_id", $request->service_provider_id)->get();
        }
        else{
            $service_provider_applications_list = ServiceProviderApplication::paginate();
        }

        return Response(json_encode($service_provider_applications_list));
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
     * @param  \App\Http\Requests\StoreServiceProviderApplicationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceProviderApplicationRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'service_provider_id' => 'required|integer',
            'services' => 'required|string',
        ]);

        $services = explode(',', $request->services);
        ServiceProviderApplication::where('service_provider_id', $request->service_provider_id)->delete();
        foreach($services as $service){
            ServiceProviderApplication::create([
                "service_id" => $service,
                "service_provider_id" => $request->service_provider_id
            ]);
        }

        $service_provider_applications = ServiceProviderApplication::where("service_provider_id", $request->service_provider_id)->get();

        return Response(json_encode([
            'message' => 'ServiceProviderApplication created successfully !',
            'applications' => $service_provider_applications
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceProviderApplication  $service_provider_application
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_service_provider_application = ServiceProviderApplication::findOrFail($id);

        return Response(json_encode($searched_service_provider_application));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceProviderApplication  $service_provider_application
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceProviderApplication $service_provider_application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateServiceProviderApplicationRequest  $request
     * @param  \App\Models\ServiceProviderApplication  $service_provider_application
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceProviderApplicationRequest $request, $id)
    {
        $searched_service_provider_application = ServiceProviderApplication::findOrFail($id);

        $request->validate([
            'service_provider_id' => 'required|integer',
            'service_id' => 'required|integer',
        ]);

        $searched_service_provider_application->service_provider_id = $request->service_provider_id;
        $searched_service_provider_application->service_id = $request->service_id;

        $searched_service_provider_application->save();

        return Response(json_encode([
            'message' => 'ServiceProviderApplication updated successfully !',
            'service_provider_application' => $searched_service_provider_application
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceProviderApplication  $service_provider_application
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_service_provider_application = ServiceProviderApplication::findOrFail($id);

        $searched_service_provider_application->delete();

        return Response(json_encode([
            'message' => 'ServiceProviderApplication deleted successfully !'
        ]));
    }
}
