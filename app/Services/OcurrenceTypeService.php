<?php

namespace App\Services;

use App\Models\OcurrenceType;
use Illuminate\Database\Eloquent\Collection;

class OcurrenceTypeService
{
    private $ocurrence_type;

    public function __construct(OcurrenceType $ocurrence_type)
    {
        $this->ocurrence_type = $ocurrence_type;
    }

    public function save(array $data): array
    {           
        if (!$this->validateStatus($data['status'])) {
            return [
                "data" => "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].",
                "success" => false 
            ];
        }
        
        $this->ocurrence_type->name = $data['name'];
        $this->ocurrence_type->status = $data['status'];
        $this->ocurrence_type->save();

        return [
            "data" => $this->ocurrence_type,
            "success" => true
        ];               
    }

    public function update(array $data, int $id): array
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        if ($data['status'] !== NULL) {
            if (!$this->validateStatus($data['status'])) {
                return [
                    "data" => "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].",
                    "success" => false 
                ];
            }
        }

        $ocurrence_type->update($data);

        return [
            "data" => $ocurrence_type,
            "success" => true
        ]; 
    }

    public function delete(int $id): bool
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        return $ocurrence_type->delete();
    }

    public function list(): Collection
    {        
        return $this->ocurrence_type->all();
    }

    public function show(int $id): OcurrenceType
    {
        return $this->ocurrence_type->find($id);
    }

    public function changeStatus(string $status, int $id): array
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        if (!$this->validateStatus($status)) {
            return [
                "data" => "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].",
                "success" => false 
            ];
        }

        $ocurrence_type->status = $status;
        $ocurrence_type->save();

        return [
            "data" => $ocurrence_type,
            "success" => true
        ]; 
    }

    public function validateStatus(string $status): bool
    {        
        $allowedTypes = ["leve", "media", "pesada"];
        if (!in_array($status, $allowedTypes)) {
            return false;
        }

        return true;
    }

    public function getType(string $name)
    {              
        $type = $this->ocurrence_type->where('name', $name)->first();   
        return $type; 
    }
}
