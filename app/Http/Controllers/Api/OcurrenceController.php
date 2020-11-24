<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OcurrenceRequest;
use Illuminate\Http\Request;
use App\Services\OcurrenceService;
use App\Models\Ocurrence;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OcurrenceResource;


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
        $this->validate($request, [
            'violence_type' => ['required'],
            'what_to_do' => ['required'],
            'type_name' => ['required']
        ]);

        $parameters = $request->all();
        $parameters['user_id'] = Auth::user()->id;             
        $ocurrence = $this->service->save($parameters);
        
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

        return response(new OcurrenceResource($show), 200);
        
    }    
}
