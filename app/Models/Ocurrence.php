<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocurrence extends Model
{
    use HasFactory;

    protected $fillable = [        
        'violence_type',
        'what_to_do',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function setViolenceTypeAttribute($value)
    {
        $this->attributes['violence_type'] = strtoupper($value);
    }

    public function getViolenceTypeAttribute()
    {
        $sanitizedViolenceType = preg_replace('/[A-Ba-b]/', '4', $this->violence_type);
        return $sanitizedViolenceType;
    }

    
}
