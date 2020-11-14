<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ocurrence extends Model
{
    use SoftDeletes;
    

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
