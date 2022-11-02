<?php

namespace App\Http\Controllers;

use App\Models\EnquiryModificationHistory;
use App\Http\Requests\StoreEnquiryModificationHistoryRequest;
use App\Http\Requests\UpdateEnquiryModificationHistoryRequest;
use Symfony\Component\HttpFoundation\Request;

class EnquiryModificationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreEnquiryModificationHistoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEnquiryModificationHistoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnquiryModificationHistory  $enquiryModificationHistory
     * @return \Illuminate\Http\Response
     */
    public function show(EnquiryModificationHistory $enquiryModificationHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EnquiryModificationHistory  $enquiryModificationHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(EnquiryModificationHistory $enquiryModificationHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEnquiryModificationHistoryRequest  $request
     * @param  \App\Models\EnquiryModificationHistory  $enquiryModificationHistory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEnquiryModificationHistoryRequest $request, EnquiryModificationHistory $enquiryModificationHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnquiryModificationHistory  $enquiryModificationHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnquiryModificationHistory $enquiryModificationHistory)
    {
        //
    }

    public function statistics(Request $request){
        $statistics = [];
        $statistics = EnquiryModificationHistory::select('state')->distinct()->get();
        foreach($statistics as $stat){
            $stat->total = EnquiryModificationHistory::where('state', $stat->state)->where('service_provider_id', $request->service_provider_id)->count();
            if($stat->state === 0)
            {
                $stat->total1 = EnquiryModificationHistory::where('state', $stat->state)->where('service_provider_id', $request->service_provider_id)->where('author', 'user')->count();
                $stat->total2 = EnquiryModificationHistory::where('state', $stat->state)->where('service_provider_id', $request->service_provider_id)->where('author', 'provider')->count();
            }
        }

        return Response(json_encode($statistics));
    }
}
