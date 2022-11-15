<?php

namespace App\Http\Controllers;

use App\Events\ProviderRequest;
use App\Events\UserEnquiry;
use App\Models\Enquiry;
use App\Models\Answer;
use App\Events\Notifications;
use App\Models\ServiceProviderApplication;
use App\Models\ServiceProvider;
use App\Http\Requests\StoreEnquiryRequest;
use App\Http\Requests\UpdateEnquiryRequest;
use App\Models\EnquiryModificationHistory;
use App\Models\Notification;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;
use Symfony\Component\HttpFoundation\Request;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $enquiries_list = [];
        $enquiries_list = isset($request->user_id) ? Enquiry::where('user_id', $request->user_id)
                                                : Enquiry::query();

        if(isset($request->code)){
            $enquiries_list = $enquiries_list->where('code', 'LIKE', "%{$request->code}%");
        }

        if(isset($request->service_provider_id)){
            $provider = ServiceProvider::findOrFail($request->service_provider_id);
            $provider_services = ServiceProviderApplication::where('service_provider_id', $request->service_provider_id)->get('service_id');
            $enquiries_list = $enquiries_list->where(function($query) {
                global $request;
                $query->where('service_provider_id', $request->service_provider_id)
                      ->orWhereNull('service_provider_id');
            })
            ->where('user_id', '!=', $provider->user_id)
            ->whereIn('service_id', $provider_services);
        }

        if(isset($request->states) && $request->states !== ''){
            $enquiries_list = $enquiries_list->whereIn('state', explode(',', $request->states));
        }

        if(isset($request->services) && $request->services !== ''){
            $enquiries_list = $enquiries_list->whereIn('service_id', explode(',', $request->services));
        }

        if(isset($request->order_by) || isset($request->order_direction)){
            $order_by = isset($request->order_by) ? $request->order_by : 'created_at';
            $order_direction = isset($request->order_direction) ? $request->order_direction : 'desc';

            $enquiries_list = $enquiries_list->orderBy($order_by, $order_direction);
        }

        $enquiries_list = $enquiries_list->paginate();
        foreach($enquiries_list as $enquiry){
            $service = Service::find($enquiry->service_id);
            $service->parent = Service::find($service->parent_id);
            $enquiry->service = $service;
            $enquiry->user = $enquiry->load('user');
            $provider = ServiceProvider::find($enquiry->service_provider_id);
            if($provider){
                $provider->user = $provider->load('user');
            }
            $enquiry->service_provider = $provider;
        }

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
            'user_intervention_date' => 'required|string',
            'state' => 'sometimes|integer',
            'answers' => 'sometimes|string',
            'service_id' => 'required|integer',
            'user_id' => 'required|integer',
            'service_provider_id' => 'sometimes|integer'
        ]);

        $enquiry = Enquiry::create(array_merge(
            $validator->validated(),
            ['code' => $this->crypto_rand_secure()]
        ));
        $answer = null;
        if($request->answers){
            $answer = Answer::create([
                'enquiry_id' => $enquiry->id,
                'content' => $request->answers
            ]);
        }

        return Response(json_encode([
            'message' => 'Enquiry created successfully !',
            'enquiry' => $enquiry,
            'answer' => $answer
        ]), 201);
    }

    private function crypto_rand_secure($min = 1000000, $max = 9999999)
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $idOrCode)
    {
        $searched_enquiry = null;
        if(!$request->is_code){
            $searched_enquiry = Enquiry::findOrFail($idOrCode);
        }
        else{
            $searched_enquiry = Enquiry::where('code', $idOrCode)->first();
        }

        if($searched_enquiry){
            $service = Service::find($searched_enquiry->service_id);
            $service->parent = Service::find($service->parent_id);
            $searched_enquiry->service = $service;
            $searched_enquiry->user = $searched_enquiry->load('user');
            $searched_enquiry->answers = $searched_enquiry->load('answers');
            $provider = ServiceProvider::find($searched_enquiry->service_provider_id);
            if($provider){
                $provider->user = $provider->load('user');
            }
            $searched_enquiry->service_provider = $provider;
        }

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

        $concerned_provider_id = $searched_enquiry->service_provider_id;

        $request->validate([
            'address' => 'sometimes|string|max:255',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'user_intervention_date' => 'sometimes|string',
            'provider_intervention_date' => 'sometimes|string|nullable',
            'user_price' => 'sometimes|string|nullable',
            'provider_price' => 'sometimes|string|nullable',
            'final_intervention_date' => 'sometimes|string|nullable',
            'final_price' => 'sometimes|string|nullable',
            'state' => 'sometimes|integer',
            'service_id' => 'sometimes|integer',
            'answers' => 'sometimes|string',
            'user_id' => 'sometimes|integer',
            'service_provider_id' => 'sometimes|integer|nullable',
            'is_provider' => 'sometimes|boolean'
        ]);

        $searched_enquiry->address = $request->address ? $request->address : $searched_enquiry->address;
        $searched_enquiry->latitude = $request->latitude ? $request->latitude : $searched_enquiry->latitude;
        $searched_enquiry->longitude = $request->longitude ? $request->longitude : $searched_enquiry->longitude;$searched_enquiry->user_id = $request->user_id ? $request->user_id : $searched_enquiry->user_id;
        $searched_enquiry->service_provider_id = ($request->service_provider_id || $request->service_provider_id === null) ? $request->service_provider_id : $searched_enquiry->service_provider_id;
        $searched_enquiry->state = isset($request->state) ? $request->state : $searched_enquiry->state;
        // $searched_enquiry->answers = $request->answers ? $request->answers : $searched_enquiry->answers;
        $searched_enquiry->final_price = ($request->final_price || $request->final_price === null) ? $request->final_price : $searched_enquiry->final_price;
        $searched_enquiry->final_intervention_date = ($request->final_intervention_date || $request->final_intervention_date === null) ? $request->final_intervention_date : $searched_enquiry->final_intervention_date;
        $searched_enquiry->user_intervention_date = $request->user_intervention_date ? $request->user_intervention_date : $searched_enquiry->user_intervention_date;
        $searched_enquiry->user_price = ($request->user_price || $request->user_price === null) ? $request->user_price : $searched_enquiry->user_price;
        $searched_enquiry->provider_intervention_date = ($request->provider_intervention_date || $request->provider_intervention_date === null) ? $request->provider_intervention_date : $searched_enquiry->provider_intervention_date;
        $searched_enquiry->provider_price = ($request->provider_price || $request->provider_price === null) ? $request->provider_price : $searched_enquiry->provider_price;



        $user_id = $searched_enquiry->user_id;

        if(!$request->is_provider){
            $temp = ServiceProvider::find($concerned_provider_id);
            $user_id = $temp ? $temp->user_id : null;
        }

        if($request->service_provider_id && $concerned_provider_id !== $request->service_provider_id){
            $concerned_provider_id = $request->service_provider_id;
        }
        $data = new stdClass();
        $data->state = $searched_enquiry->state;
        $data->enquiry_code = $searched_enquiry->code;

        $searched_enquiry->save();

        EnquiryModificationHistory::create([
            'author' => (isset($request->is_provider) && $request->is_provider === true) ? ('provider') : ((isset($request->is_provider) && $request->is_provider === false) ? ('user') : ''),
            'code' => $searched_enquiry->code,
            'user_intervention_date' => $searched_enquiry->user_intervention_date,
            'user_price' => $searched_enquiry->user_price,
            'provider_intervention_date' => $searched_enquiry->provider_intervention_date,
            'provider_price' => $searched_enquiry->provider_price,
            'final_intervention_date' => $searched_enquiry->final_intervention_date,
            'final_price' => $searched_enquiry->final_price,
            'state' => $searched_enquiry->state,
            'service_id' => $searched_enquiry->service_id,
            'user_id' => $searched_enquiry->user_id,
            'service_provider_id' => $concerned_provider_id
        ]);

        $elt = null;
        if(isset($request->is_provider) && $request->is_provider === false){
            $elt = Notification::create([
                'user_id' => $user_id,
                'reason' => 'provider-request',
                'data' => json_encode($data)
            ]);

            event(new ProviderRequest($elt));
        }
        else if (isset($request->is_provider) && $request->is_provider === true){
            $elt = Notification::create([
                'user_id' => $user_id,
                'reason' => 'user-enquiry',
                'data' => json_encode($data)
            ]);

            event(new UserEnquiry($elt));
        }

        return Response(json_encode([
            'message' => 'Enquiry updated successfully !',
            'enquiry' => $searched_enquiry,
            'temp' => $user_id
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
