<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OcurrenceRequest;
use Illuminate\Http\Request;
use App\Services\OcurrenceService;
use App\Models\Ocurrence;

class OcurrenceController extends Controller
{
    private $service;

    public function __construct(OcurrenceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return "nois";
    }

    public function store(Request $request)
    {       
        $ocurrence = $this->service->save($request->all());
    }
    
}
