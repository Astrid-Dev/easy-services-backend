<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Http\Requests\StoreEnquiryRequest;
use App\Http\Requests\UpdateEnquiryRequest;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enquiries_list = Enquiry::paginate();

        return Response(json_encode($enquiries_list));
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
     * @param  \App\Http\Requests\StoreEnquiryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEnquiryRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'creation_date' => 'required|string',
            'intervention_date' => 'sometimes|string',
            'state' => 'sometimes|integer',
            'enquiry_type_id' => 'required|integer',
            'habitation_id' => 'required|integer',
            'user_id' => 'required|integer',
            'service_provider_id' => 'sometimes|integer',
        ]);

        $enquiry = Enquiry::create($validator->validated());

        return Response(json_encode([
            'message' => 'Enquiry created successfully !',
            'enquiry' => $enquiry
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_enquiry = Enquiry::findOrFail($id);

        return Response(json_encode($searched_enquiry));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(Enquiry $enquiry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEnquiryRequest  $request
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEnquiryRequest $request, $id)
    {
        $searched_enquiry = Enquiry::findOrFail($id);

        $request->validate([
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'creation_date' => 'required|string',
            'intervention_date' => 'required|string',
            'state' => 'sometimes|integer',
            'enquiry_type_id' => 'required|integer',
            'habitation_id' => 'required|integer',
            'user_id' => 'required|integer',
            'service_provider_id' => 'sometimes|integer',
        ]);

        $searched_enquiry->address = $request->address;
        $searched_enquiry->latitude = $request->latitude;
        $searched_enquiry->longitude = $request->longitude;
        $searched_enquiry->creation_date = $request->creation_date;
        $searched_enquiry->intervention_date = $request->intervention_date;
        $searched_enquiry->enquiry_type_id = $request->enquiry_type_id;
        $searched_enquiry->user_id = $request->user_id;
        $searched_enquiry->habitation_id = $request->habitation_id;
        $searched_enquiry->service_provider_id = $request->service_provider_id ? $request->service_provider_id : $searched_enquiry->service_provider_id;
        $searched_enquiry->state = $request->state ? $request->state : $searched_enquiry->state;

        $searched_enquiry->save();

        return Response(json_encode([
            'message' => 'Enquiry updated successfully !',
            'enquiry' => $searched_enquiry
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_enquiry = Enquiry::findOrFail($id);

        $searched_enquiry->delete();

        return Response(json_encode([
            'message' => 'Enquiry deleted successfully !'
        ]));
    }
}
