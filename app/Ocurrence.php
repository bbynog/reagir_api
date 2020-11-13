<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ocurrence extends Model
{
    protected $fillable = [
      'violence_type',
      'what_to_do'
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
      'deleted_at',
    ];
}
