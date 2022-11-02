<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->service_id){
            $question = Question::where("service_id", $request->service_id)->first();

            return Response(json_encode($question));
        }
        else{
            $questions_list = Question::paginate();
            return Response(json_encode($questions_list));
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
     * @param  \App\Http\Requests\StoreQuestionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuestionRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'service_id' => 'required|integer',
        ]);

        $question = Question::create($validator->validated());

        return Response(json_encode([
            'message' => 'Question created successfully !',
            'question' => $question
        ]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $searched_question = Question::findOrFail($id);

        return Response(json_encode($searched_question));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuestionRequest  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestionRequest $request, $id)
    {
        $searched_question = Question::findOrFail($id);

        $request->validate([
            'content' => 'required|string',
            'service_id' => 'required|integer',
        ]);

        $searched_question->content = $request->content;
        $searched_question->service_id = $request->service_id;

        $searched_question->save();

        return Response(json_encode([
            'message' => 'Question updated successfully !',
            'question' => $searched_question
        ]), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $searched_question = Question::findOrFail($id);

        $searched_question->delete();

        return Response(json_encode([
            'message' => 'Question deleted successfully !'
        ]));
    }
}
