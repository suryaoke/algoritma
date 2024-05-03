<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $table   = 'lecturers';
    protected $guarded = [];


    public function users()
    {
        return $this->belongsTo(User::class, 'akun');
    }

 
}
