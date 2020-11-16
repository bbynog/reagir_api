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
        $list = $this->service->list();
        
        return response($list, 200);
    }

    public function store(Request $request)
    {          
        $ocurrence = $this->service->save($request->all());
        
        return response($ocurrence, 200);
    }

    public function update(Request $request, $id)
    {
        $ocurrence = $this->service->update($request->all(), $id);

        return response($ocurrence, 200);
    }

    public function delete(Request $request, $id)
    {
        $ocurrence = $this->service->delete($id);

        return response($ocurrence, 200);
    }

    public function show($id)
    {
        $show = $this->service->show($id);

        return response($show, 200);
    }
    
}
