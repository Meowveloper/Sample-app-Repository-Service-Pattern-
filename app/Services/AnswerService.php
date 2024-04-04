<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Problem;
use App\Models\User;

class AnswerService
{
    
    public function saveAnswer($data)
    {
        $problemId = $data['problem_id'];
        $problemOptionId = $data['problem_option_id'];
        $userId = $data['userId'];
        $problem = Problem::find($problemId);
        $score = 0;
        //check if user answered option is correct
        if($problem->correct_option_id == $problemOptionId) {
            //if correct, plus one point
            $score = 1;
        }
        //create answer
        $answer = Answer::create([
            'user_id' => $userId,
            'problem_id' => $problemId,
            'point' => $score
        ]);

        //update user point
        User::where('id', $userId)->increment('point', $score);
        return $answer;
    }
}