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
}