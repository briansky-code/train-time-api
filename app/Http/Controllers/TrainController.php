<?php

namespace App\Http\Controllers;


use App\Trains;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TrainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'train_id' => 'required|numeric',
            'limit' => 'numeric'
        ]);

        if ($validator->fails()) {
            $response = array('message' => $validator->errors(), 'status' => 400);
            return response()->json($response)->setStatusCode(400, 'Bad Request');
        }

        if ($request->has('limit')) {
            $limit = $request->limit;
        } else {
            $limit = 5;
        }

        $data = Trains::where('train_id', $request->train_id)
            ->take($limit)
            ->orderBy('created_at', 'desc')
            ->get(['sched', 'train_id', 'track']);

        if($data->isEmpty()) {
            $response = array('message' => 'Data not found', 'status' => 404);
            return response()->json($response)->setStatusCode(404, 'Data not found');
        }

        return response()->json($data)->header('Content-Type', 'json');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
