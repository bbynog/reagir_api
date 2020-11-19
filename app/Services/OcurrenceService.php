<?php

namespace App\Services;

use App\Models\Ocurrence;

class OcurrenceService
{
    private $ocurrence;

    public function __construct(Ocurrence $ocurrence)
    {
        $this->ocurrence = $ocurrence;
    }

    public function save(array $data)
    {        
        $this->ocurrence->violence_type = $data['violence_type'];
        $this->ocurrence->what_to_do = $data['what_to_do'];
        $this->ocurrence->user_id = $data['user_id'];
        #$this->ocurrence->ocurrence_type_id = $data['user_id'];
        $this->ocurrence->save();
        
        return $this->ocurrence;
    }

    public function update(array $data, $id)
    {
        $ocurrence = $this->ocurrence->find($id);
        $ocurrence->update($data);

        return $ocurrence;
    }

    public function delete($id)
    {
        $ocurrence = $this->ocurrence->find($id);
        $deleted = $ocurrence->delete();

        return $deleted;
    }

    public function list()
    {        
        return $this->ocurrence->all();
    }

    public function show($id)
    {        
        return $this->ocurrence->find($id);
    }
    

}