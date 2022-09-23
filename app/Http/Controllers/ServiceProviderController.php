<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use App\Http\Requests\StoreServiceProviderRequest;
use App\Http\Requests\UpdateServiceProviderRequest;
use Illuminate\Support\Facades\Validator;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services_providers_list = ServiceProvider::paginate();

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
            'username' => 'required|string|between:3,20|unique:service_providers',
            'email' => 'required|email|max:100',
            'password' => 'required|string|between:6,20',
        ]);

        $service_provider = ServiceProvider::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return Response(json_encode([
            'message' => 'Service provider created successfully !',
            'service_provider' => $service_provider
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
        $searched_service_provider = ServiceProvider::findOrFail($id);

        $request->validate([
            'username' => 'required|string|between:3,20|unique:service_providers',
            'email' => 'required|email|max:60',
        ]);

        $searched_service_provider->username = $request->username;
        $searched_service_provider->email = $request->email;

        $searched_service_provider->save();

        return Response(json_encode([
            'message' => 'Service provider updated successfully !',
            'service_provider' => $searched_service_provider
        ]), 201);
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
