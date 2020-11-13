<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OcurrenceRequest;

class OcurrenceController extends Controller
{
    public function index()
    {
        return "nois";
    }

    public function store(OcurrenceRequest $request)
    {
        dd($request->all());
    }
}
