<?php

namespace App\Http\Controllers;

use App\Models\OrganizationApplication;
use App\Http\Requests\StoreOrganizationApplicationRequest;
use App\Http\Requests\UpdateOrganizationApplicationRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class OrganizationApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $organization_applications_list = [];

        if($request->organization_id){
            $organization_applications_list = OrganizationApplication::where("organization_id", $request->organization_id)->get();
        }
        else{
            $organization_applications_list = OrganizationApplication::paginate();
        }

        return Response(json_encode($organization_applications_list));
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
     * @param  \App\Http\Requests\StoreOrganizationApplicationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrganizationApplicationRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_id' => 'required|integer',
            'services' => 'required|string',
        ]);

        $services = explode(',', $request->services);
        OrganizationApplication::where('organization_id', $request->organization_id)->delete();
        foreach($services as $service){
            OrganizationApplication::create([
                "service_id" => $service,
                "organization_id" => $request->organization_id
            ]);
        }

        $organization_applications = OrganizationApplication::where("organization_id", $request->organization_id)->get();

        return Response(json_encode([
            'message' => 'OrganizationApplication created successfully !',
            'applications' => $organization_applications
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrganizationApplication  $organization_application
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_organization_application = OrganizationApplication::findOrFail($id);

        return Response(json_encode($searched_organization_application));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrganizationApplication  $organization_application
     * @return \Illuminate\Http\Response
     */
    public function edit(OrganizationApplication $organization_application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrganizationApplicationRequest  $request
     * @param  \App\Models\OrganizationApplication  $organization_application
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrganizationApplicationRequest $request, $id)
    {
        $searched_organization_application = OrganizationApplication::findOrFail($id);

        $request->validate([
            'organization_id' => 'required|integer',
            'service_id' => 'required|integer',
        ]);

        $searched_organization_application->organization_id = $request->organization_id;
        $searched_organization_application->service_id = $request->service_id;

        $searched_organization_application->save();

        return Response(json_encode([
            'message' => 'OrganizationApplication updated successfully !',
            'organization_application' => $searched_organization_application
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrganizationApplication  $organization_application
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_organization_application = OrganizationApplication::findOrFail($id);

        $searched_organization_application->delete();

        return Response(json_encode([
            'message' => 'OrganizationApplication deleted successfully !'
        ]));
    }
}
