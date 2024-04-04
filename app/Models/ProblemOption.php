<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemOption extends Model
{
    use HasFactory;
    protected $table = 'problem_options';
    protected $fillable = ['content','imageUrl','problem_id','created_at','updated_at'];

    public function problem()
    {
        return $this->belongsTo(Problem::class, 'problem_id');
    }
}
