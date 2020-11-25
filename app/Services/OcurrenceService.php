<?php

namespace App\Services;

use App\Models\Ocurrence;
use App\Models\OcurrenceType;
use App\Services\OcurrenceTypeService;
use App\Http\Resources\OcurrenceResource;
use Error;

class OcurrenceService
{
    private $ocurrence;
    private $ocurrenceTypeService;

    const FIELDS = ['violence_type', 'what_to_do', 'type_name'];

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
        if (!$this->validateFields($data)) {
            return [
                "response" => [
                    "data" => "Need to choose at least one field to update. [" . implode(', ', self::FIELDS) . "]",
                    "success" => false,
                ], 
                "status_code" => 422
            ];
        }

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
            "response" => [
                "data" => new OcurrenceResource($ocurrence),
                "success" => true 
            ],
            "status_code" => 200
        ];
    }

    public function delete($id)
    {
        $ocurrence = $this->ocurrence->find($id);
        if ($ocurrence === null) {
            return [
                "response" => [
                    "data" => "Inexistent Ocurrence ID. Please enter a valid one.",
                    "success" => false,
                ],
                "status_code" => 422
            ];
        }

        $ocurrence->delete();

        return [
            "response" => [
                "data" => $ocurrence,
                "success" => true,
            ],
            "status_code" => 200
        ];
    }

    public function list()
    {        
        return OcurrenceResource::collection($this->ocurrence->all());
    }

    public function show($id)
    {       
        $ocurrence = $this->ocurrence->find($id);
        if ($ocurrence === null ) {
            return [
                "response" => [
                    "data" => "Inexistent Ocurrence ID. Please enter a valid one.",
                    "success" => false,
                ],
                "status_code" => 422
            ];
        } 

        return [
            "response" => [
                "data" => new OcurrenceResource($ocurrence),
                "success" => true,
            ],
            "status_code" => 200
        ];   
    }

    private function validateFields(array $data): bool
    {
        foreach(self::FIELDS as $value) {
            if (array_key_exists($value, $data)) {
                return true;
            }
        }

        return false;
    }
}