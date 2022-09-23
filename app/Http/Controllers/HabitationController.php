<?php

namespace App\Http\Controllers;

use App\Models\Habitation;
use App\Http\Requests\StoreHabitationRequest;
use App\Http\Requests\UpdateHabitationRequest;
use Illuminate\Support\Facades\Validator;

class HabitationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $habitations_list = Habitation::paginate();

        return Response(json_encode($habitations_list));
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
     * @param  \App\Http\Requests\StoreHabitationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHabitationRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255|unique:habitations',
            'label_en' => 'sometimes|string|max:255|unique:habitations',
        ]);

        $habitation = Habitation::create($validator->validated());

        return Response(json_encode([
            'message' => 'Habitation created successfully !',
            'habitation' => $habitation
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Habitation  $habitation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_habitation = Habitation::findOrFail($id);

        return Response(json_encode($searched_habitation));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Habitation  $habitation
     * @return \Illuminate\Http\Response
     */
    public function edit(Habitation $habitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateHabitationRequest  $request
     * @param  \App\Models\Habitation  $habitation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHabitationRequest $request, $id)
    {
        $searched_habitation = Habitation::findOrFail($id);

        $request->validate([
            'label' => 'required|string|max:255|unique:habitations',
            'label_en' => 'sometimes|string|max:255|unique:habitations',
        ]);

        $searched_habitation->label = $request->label;
        $searched_habitation->label_en = $request->label_en ? $request->label_en : $searched_habitation->label_en;

        $searched_habitation->save();

        return Response(json_encode([
            'message' => 'Habitation updated successfully !',
            'habitation' => $searched_habitation
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Habitation  $habitation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_habitation = Habitation::findOrFail($id);

        $searched_habitation->delete();

        return Response(json_encode([
            'message' => 'Habitation deleted successfully !'
        ]));
    }
}
