<?php

namespace App\Repositories;

use App\Models\ProblemOption;

class ProblemOptionRepository
{
    public function getAllProblemOptionsCount(){
        return ProblemOption::count();
    }

    public function getProblemOptionTableData(){
        return ProblemOption::orderBy('id','desc')->get();
    }

    public function getAllProblemOptionsData($search){
        return ProblemOption::when(isset($search), function($query) use ($search){
            $query->where('content', 'like', '%' . $search . '%');
        })->orderBy('id','desc')->get();
    }

    public function getProblemOptionData($id)
    {
        return ProblemOption::where('problem_id', $id)->select('id','content')->get();
    }
}
