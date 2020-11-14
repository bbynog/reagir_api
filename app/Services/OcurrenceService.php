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
        $this->ocurrence->save();
        
        return $this->ocurrence;
    }

    public function update(array $data, $id)
    {
        $ocurrence = $this->ocurrence->find($id);
        $ocurrence->update($data);

        return $ocurrence;
    }

    public function delete(array $data, $id)
    {
        $ocurrence = $this->ocurrence->find($id);
        $ocurrence->delete();

        return $ocurrence['deleted_at'];
    }
}