<?php

namespace App\Services;

use App\Models\ProblemOption;

class ProblemOptionService
{
    public function updateProblemOption($data,$id){
        return ProblemOption::where('id',$id)->update(['content' => $data['content']]);

    }
}
