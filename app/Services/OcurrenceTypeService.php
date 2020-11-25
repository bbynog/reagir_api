<?php

namespace App\Services;

use App\Models\OcurrenceType;
use Illuminate\Database\Eloquent\Collection;

class OcurrenceTypeService
{
    private $ocurrence_type;

    const FIELDS = ['name', 'status'];

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
        if (!$this->validateFields($data)) {
            return [
                "response" => [
                    "data" => "Need to choose at least one field to update. [" . implode(', ', self::FIELDS) . "]",
                    "success" => false,
                ], 
                "status_code" => 422
            ];
        }

        if (array_key_exists('status', $data)) {
            if (!$this->validateStatus($data['status'])) {
                return [
                    "response" => [
                        "data" => "Invalid Status Type. Accept only ['leve', 'media', 'pesada'].",
                        "success" => false,
                    ],
                    "status_code" => 422
                ];
            }
        }

        $ocurrence_type = $this->ocurrence_type->find($id);
        $ocurrence_type->update($data);

        return [
            "response" => [
                "data" => $ocurrence_type,
                "success" => true,
            ],
            "status_code" => 200,
        ]; 
    }

    public function delete(int $id): array
    {
        $ocurrence_type = $this->ocurrence_type->find($id);
        if ($ocurrence_type === null) {
            return [
                "response" => [
                    "data" => "Inexistent Type ID. Please enter a valid one.",
                    "success" => false,
                ],
                "status_code" => 422
            ];
        }
        return [
            "response" => [
                "data" => $ocurrence_type,
                "success" => true,
            ],
            "status_code" => 200
        ];
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
