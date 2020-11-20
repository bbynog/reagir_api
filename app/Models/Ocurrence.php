<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ocurrence extends Model
{
    use SoftDeletes;    

    protected $fillable = [
      'user_id',
      'type_id',
      'violence_type',
      'what_to_do'
    ];

    protected $hidden = [
      'created_at',
      'updated_at',
      'deleted_at',
    ];
    

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function type()
    {
      return $this->belongsTo(OcurrenceType::class);
    }


}
