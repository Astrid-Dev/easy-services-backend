<?php

namespace App\Http\Controllers;

use App\Models\EnquiryType;
use App\Http\Requests\StoreEnquiryTypeRequest;
use App\Http\Requests\UpdateEnquiryTypeRequest;
use Illuminate\Support\Facades\Validator;

class EnquiryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enquiries_types_list = EnquiryType::paginate();

        return Response(json_encode($enquiries_types_list));
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
     * @param  \App\Http\Requests\StoreEnquiryTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEnquiryTypeRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255|unique:enquiry_types',
            'label_en' => 'sometimes|string|max:255|unique:enquiry_types',
        ]);

        $enquiry_type = EnquiryType::create($validator->validated());

        return Response(json_encode([
            'message' => 'Enquiry type created successfully !',
            'enquiry_type' => $enquiry_type
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnquiryType  $enquiry_type
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_enquiry_type = EnquiryType::findOrFail($id);

        return Response(json_encode($searched_enquiry_type));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EnquiryType  $enquiry_type
     * @return \Illuminate\Http\Response
     */
    public function edit(EnquiryType $enquiry_type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEnquiryTypeRequest  $request
     * @param  \App\Models\EnquiryType  $enquiry_type
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEnquiryTypeRequest $request, $id)
    {
        $searched_enquiry_type = EnquiryType::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255|unique:enquiry_types',
            'label_en' => 'sometimes|string|max:255|unique:enquiry_types',
        ]);

        $searched_enquiry_type->label = $request->label;
        $searched_enquiry_type->label_en = $request->label_en ? $request->label_en : $searched_enquiry_type->label_en;

        $searched_enquiry_type->save();

        return Response(json_encode([
            'message' => 'Enquiry type updated successfully !',
            'enquiry_type' => $searched_enquiry_type
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnquiryType  $enquiry_type
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_enquiry_type = EnquiryType::findOrFail($id);

        $searched_enquiry_type->delete();

        return Response(json_encode([
            'message' => 'Enquiry type deleted successfully !'
        ]));
    }
}
