<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table   = 'rooms';
    protected $guarded = [];

    public function jurusans()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan');
    }
}
