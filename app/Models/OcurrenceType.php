<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OcurrenceType extends Model
{
    use SoftDeletes;

    protected $fillable = [        
        'name',
        'status'        
      ];
  
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
      ];

    public function ocurrences()
    {
        return $this->hasMany(Ocurrence::class);
    }
}
