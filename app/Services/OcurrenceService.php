<?php

namespace App\Services;

use App\Models\Ocurrence;
use App\Models\OcurrenceType;
use App\Services\OcurrenceTypeService;
use App\Http\Resources\OcurrenceResource;

class OcurrenceService
{
    private $ocurrence;
    private $ocurrenceTypeService;

    public function __construct(Ocurrence $ocurrence, OcurrenceTypeService $ocurrenceTypeService)
    {
        $this->ocurrence = $ocurrence;
        $this->ocurrenceTypeService = $ocurrenceTypeService;
    }

    public function save(array $data)
    {     
        $type = $this->ocurrenceTypeService->getType($data['type_name']);         
        if (is_null($type)) {
            return [
                "data" => "Type doesn't exist.",
                "success" => false 
            ];
        }

        $this->ocurrence->violence_type = $data['violence_type'];
        $this->ocurrence->what_to_do = $data['what_to_do'];
        $this->ocurrence->user_id = $data['user_id'];
        $this->ocurrence->type_id = $type->id;        
        $this->ocurrence->save();
        
        return [
            "data" => new OcurrenceResource($this->ocurrence),
            "success" => true 
        ];
    }

    public function update(array $data, $id)
    {
        if (!empty($data['type_name'])) {
            $type = $this->ocurrenceTypeService->getType($data['type_name']);         
            if (is_null($type)) {                
                return [
                    "data" => "Type doesn't exist.",
                    "success" => false
                ];
            }  
            $data['type_id'] = $type->id;
            unset($data['type_name']);          
        }        
        
        $ocurrence = $this->ocurrence->find($id);        
        $ocurrence->update($data);
        
        return [
            "data" => new OcurrenceResource($ocurrence),
            "success" => true 
        ];
    }

    public function delete($id)
    {
        $ocurrence = $this->ocurrence->find($id);
        $deleted = $ocurrence->delete();

        return $deleted;
    }

    public function list()
    {        
        return OcurrenceResource::collection($this->ocurrence->all());
    }

    public function show($id)
    {        
        return new OcurrenceResource($this->ocurrence->find($id));
        
    }   
}