<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->enquiry_id){
            $answer = Answer::where("enquiry_id", $request->enquiry_id)->first();

            return Response(json_encode($answer));
        }
        else{
            $answers_list = Answer::paginate();
            return Response(json_encode($answers_list));
        }
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
     * @param  \App\Http\Requests\StoreAnswerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnswerRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'enquiry_id' => 'required|integer',
        ]);

        $answer = Answer::create($validator->validated());

        return Response(json_encode([
            'message' => 'Answer created successfully !',
            'answer' => $answer
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_answer = Answer::findOrFail($id);

        return Response(json_encode($searched_answer));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Answer $answer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAnswerRequest  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAnswerRequest $request, $id)
    {
        $searched_answer = Answer::findOrFail($id);

        $request->validate([
            'content' => 'required|string',
            'enquiry_id' => 'required|integer',
        ]);

        $searched_answer->content = $request->content;
        $searched_answer->enquiry_id = $request->enquiry_id;

        $searched_answer->save();

        return Response(json_encode([
            'message' => 'Answer updated successfully !',
            'answer' => $searched_answer
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_answer = Answer::findOrFail($id);

        $searched_answer->delete();

        return Response(json_encode([
            'message' => 'Answer deleted successfully !'
        ]));
    }
}
