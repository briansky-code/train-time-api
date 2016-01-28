<?php

namespace App\Http\Controllers;

use App\TrainTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class Controller extends Controller
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
            'endsta' => 'required|size:3',
        ]);

        if ($validator->fails()) {
            $response = array('message' => $validator->errors(), 'status' => 400);
            return response()->json($response)->setStatusCode(400, 'Bad Request');
        }

        $data = TrainTime::where('name', $request->endsta)
            ->orderBy('created_at', 'desc')
            ->first(['name', 'data']);

        if(!$data) {
            $response = array('message' => 'Data not found', 'status' => 404);
            return response()->json($response)->setStatusCode(404, 'Data not found');
        }

        $response = array('name' => $data->name, 'data' => json_decode($data->data, true));
        return response()->json($response)->header('Content-Type', 'json');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
