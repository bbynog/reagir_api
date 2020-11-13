<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OcurrenceRequest;

class OcurrenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "nois";
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OcurrenceRequest $request)
    {
        /*dd("dasd");
        dd($request->all());*/
        //dd('coming..');
        //$validated = $request->validated();
        $type_of_violence = $request->input("type_of_violence");
        $what_to_do = $request->input("what_to_do");
        dd($request);
        

       
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        return view('welcome', ['id' => $id]);
    }*/

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
