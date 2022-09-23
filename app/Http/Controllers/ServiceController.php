<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services_list = Service::paginate();

        return Response(json_encode($services_list));
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
     * @param  \App\Http\Requests\StoreServiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255|unique:services',
            'parent_id' => 'sometimes|integer',
            'label_en' => 'sometimes|string|max:255|unique:services',
        ]);

        $service = Service::create($validator->validated());

        return Response(json_encode([
            'message' => 'Service created successfully !',
            'service' => $service
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_service = Service::findOrFail($id);

        return Response(json_encode($searched_service));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateServiceRequest  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceRequest $request, $id)
    {
        $searched_service = Service::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255|unique:services',
            'parent_id' => 'sometimes|integer',
            'label_en' => 'sometimes|string|max:255|unique:services',
        ]);

        $searched_service->label = $request->label;
        $searched_service->label_en = $request->label_en ? $request->label_en : $searched_service->label_en;
        $searched_service->parent_id = $request->parent_id ? $request->parent_id : $searched_service->parent_id;

        $searched_service->save();

        return Response(json_encode([
            'message' => 'Service updated successfully !',
            'service' => $searched_service
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_service = Service::findOrFail($id);

        $searched_service->delete();

        return Response(json_encode([
            'message' => 'Service deleted successfully !'
        ]));
    }
}
