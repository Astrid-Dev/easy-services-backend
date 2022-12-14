<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\OrganizationApplication;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations_list = Organization::paginate();

        return Response(json_encode($organizations_list));
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
     * @param  \App\Http\Requests\StoreOrganizationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrganizationRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|unique:organizations',
            'name' => 'required|string',
            'phone_number1' => 'required|string|between:12,18',
            'phone_number2' => 'sometimes|string|between:12,18',
            'email1' => 'required|string|email|max:100|',
            'email2' => 'sometimes|string|email|max:100',
            'facebook' => 'sometimes|string|max:100',
            'twitter' => 'sometimes|string|max:100',
            'instagram' => 'sometimes|string|max:100',
            'website' => 'sometimes|string|max:100',
            'description' => 'required|string|max:500',
            'description_en' => 'required|string|max:500'
        ]);

        $user = User::findOrFail($request->user_id);
        if($user->role !== 'SIMPLE_USER'){
            abort(403);
        }

        $organization = Organization::create(array_merge(
            $validator->validated(),
            ['code' => 'ORG'.$this->crypto_rand_secure()]
        ));

        if ($logo = $request->file('logo')) {
            $destinationPath = public_path().'/images/';
            $filename = 'enterprise_'.$organization->id.'_logo_'.$this->crypto_rand_secure().'.'.$logo->getClientOriginalExtension();
            $logo->move($destinationPath, $filename);
            // final url to store into database
            $saved_path = "images/".$filename;

            $organization->logo = $saved_path;
            $organization->save();
        }


        $user->role = 'ORGANIZATION';
        $user->save();

        if($request->services){
            $services = explode(',', $request->services);

            foreach($services as $service){
                OrganizationApplication::create([
                    "service_id" => $service,
                    "organization_id" => $organization->id
                ]);
            }
        }

        $organization_applications = OrganizationApplication::where("organization_id", $organization->id)->get();

        return Response(json_encode([
            'message' => 'Organization created successfully !',
            'organization' => $organization,
            'user' => $user,
            'applications' => $organization_applications
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_organization = Organization::findOrFail($id);
        $searched_organization->applications = $searched_organization->load('applications');
        $searched_organization->employees_number = $searched_organization->load('employees')->count();

        return Response(json_encode($searched_organization));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organization  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $serviceProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrganizationRequest  $request
     * @param  \App\Models\Organization  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrganizationRequest $request, $id)
    {
        $searched_organization = Organization::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string',
            'phone_number1' => 'sometimes|string|between:12,18',
            'phone_number2' => 'sometimes|string|between:12,18',
            'email1' => 'sometimes|string|email|max:100|',
            'email2' => 'sometimes|string|email|max:100',
            'facebook' => 'sometimes|string|max:100',
            'twitter' => 'sometimes|string|max:100',
            'instagram' => 'sometimes|string|max:100',
            'website' => 'sometimes|string|max:100',
            'description' => 'sometimes|string|max:500',
            'description_en' => 'sometimes|string|max:500'
        ]);

        $searched_organization->name = $request->name ? $request->name : $searched_organization->name;
        $searched_organization->phone_number1 = $request->phone_number1 ? $request->phone_number1 : $searched_organization->phone_number1;
        $searched_organization->phone_number2 = $request->phone_number2 ? $request->phone_number2 : $searched_organization->phone_number2;
        $searched_organization->email1 = $request->email1 ? $request->email1 : $searched_organization->email1;
        $searched_organization->email2 = $request->email2 ? $request->email2 : $searched_organization->email2;
        $searched_organization->facebook = $request->facebook ? $request->facebook : $searched_organization->facebook;
        $searched_organization->twitter = $request->twitter ? $request->twitter : $searched_organization->twitter;
        $searched_organization->instagram = $request->instagram ? $request->instagram : $searched_organization->instagram;
        $searched_organization->website = $request->website ? $request->website : $searched_organization->website;
        $searched_organization->description = $request->description ? $request->description : $searched_organization->description;
        $searched_organization->description_en = $request->description_en ? $request->description_en : $searched_organization->description_en;

        $saved_path= ($searched_organization->logo && $searched_organization->logo !== '') ? $searched_organization->logo : null;

        if ($logo = $request->file('logo')) {
            $destinationPath = public_path().'/images/';
            $filename = 'enterprise_'.$searched_organization->id.'_logo_'.$this->crypto_rand_secure().$logo->getClientOriginalExtension();
            $logo->move($destinationPath, $filename);
            // final url to store into database
            $saved_path = "images/".$filename;
        }

        $searched_organization->logo = $saved_path;

        $searched_organization->save();
        if($request->services){
            OrganizationApplication::where('organization_id', $searched_organization->id)->delete();
            $services = explode(',', $request->services);

            foreach($services as $service){
                OrganizationApplication::create([
                    "service_id" => $service,
                    "organization_id" => $searched_organization->id
                ]);
            }
        }

        $organization_applications = OrganizationApplication::where("organization_id", $searched_organization->id)->get();

        $user = $searched_organization->load('user');

        return Response(json_encode([
            'message' => 'Organization updated successfully !',
            'organization' => $searched_organization,
            'user' => $user,
            'applications' => $organization_applications
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_organization = Organization::findOrFail($id);

        $searched_organization->delete();

        return Response(json_encode([
            'message' => 'Organization deleted successfully !'
        ]));
    }

    private function crypto_rand_secure($min = 10000000, $max = 99999999)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }
}
