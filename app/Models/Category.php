<?php

namespace App\Models;

use App\Models\Problem;
use App\Models\ProblemOption;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['name','created_at','updated_at'];

    public function problems()
    {
        return $this->hasMany(Problem::class);
    }
}
