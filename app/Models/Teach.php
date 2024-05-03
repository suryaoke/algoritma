<?php namespace App\Models;

use App\Models\Course;
use App\Models\Guru;
use Illuminate\Database\Eloquent\Model;

class Teach extends Model
{
    protected $table   = 'teachs';
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'gurus_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courses_id');
    }
    public function gurus()
    {
        return $this->belongsTo(Guru::class, 'gurus_id');
    }
}