<?php

namespace App\Services;

use App\Models\OcurrenceType;

class OcurrenceTypeService
{
    private $ocurrence_type;

    public function __construct(OcurrenceType $ocurrence_type)
    {
        $this->ocurrence_type = $ocurrence_type;
    }

    public function save(array $data)
    {        
        $this->ocurrence_type->name = $data['name'];
        $this->ocurrence_type->status = $data['status'];               
        $this->ocurrence_type->save();
        
        return $this->ocurrence_type;
    }

    public function update(array $data, $id)
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        $ocurrence_type->update($data);

        return $ocurrence_type;
    }

    public function delete($id)
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        $deleted = $ocurrence_type->delete();

        return $deleted;
    }

    public function list()
    {        
        return $this->ocurrence_type->all();
    }

    public function show($id)
    {        
        return $this->ocurrence_type->find($id);
    }
    

}