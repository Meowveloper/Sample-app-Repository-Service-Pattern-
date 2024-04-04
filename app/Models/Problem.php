<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Problem extends Model
{
    use HasFactory;
    protected $table = 'problems';
    protected $fillable = ['content_image_url','expected_time_to_complete','correct_option_id','category_id','created_at','updated_at'];

    public function problemOptions()
    {
        return $this->hasMany(ProblemOption::class);
    }

    public function correctOption()
    {
        return $this->belongsTo(ProblemOption::class, 'correct_option_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
