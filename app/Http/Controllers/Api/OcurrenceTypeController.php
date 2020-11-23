<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OcurrenceTypeService;
use App\Http\Resources\OcurrenceTypeResource;

class OcurrenceTypeController extends Controller
{
    private $service;

    public function __construct(OcurrenceTypeService $service)
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
        $this->validate($request, [
            'name' => ['required'],
            'status' => ['required'],
        ]);
            
        $parameters = $request->all();        
        $ocurrence_type = $this->service->save($parameters);
        
        return response($ocurrence_type, 200);
    }

    public function update(Request $request, $id)
    {
        $ocurrence_type = $this->service->update($request->all(), $id);

        return response($ocurrence_type, 200);
    }

    public function delete(Request $request, $id)
    {
        $ocurrence_type = $this->service->delete($id);

        return response($ocurrence_type, 200);
    }

    public function show($id)
    {
        $show = $this->service->show($id);

        return response(new OcurrenceTypeResource($show), 200);
    }

    public function changeStatus(Request $request, $id)
    {
        $ocurrence_type = $this->service->changeStatus($request->get('status'), $id);

        return response($ocurrence_type, 200);
    }
}
