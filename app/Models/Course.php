<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table   = 'courses';
    protected $guarded = [];

    public function jurusans()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan');
    }
}
