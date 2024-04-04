<?php

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Problem;
use App\Models\Category;
use App\Models\ProblemOption;

class ProblemRepository
{
    public function getAllProblemsCount(){
        return Problem::count();
    }

    public function getAllProblems()
    {
        return Problem::with(['category' => function ($query) {
            $query->select('id', 'name');
        }, 'correctOption' => function ($query) {
            $query->select('id', 'content'); // Assuming 'name' is the attribute you want to include
        },])->orderBy('id', 'desc')->get()->map(function ($data) {
            $data['imageUrl'] = null;
            if ($data->content_image_url) {
                $imageUrl = asset('/storage/problems/' . $data->content_image_url);
                $data['imageUrl'] = $imageUrl;
            }
            return $data;
        });
    }

    public function getProblem($id){
        return Problem::where('id',$id)->with(['correctOption' => function ($query) {
            $query->select('id', 'content'); // Assuming 'name' is the attribute you want to include
        }])->first();
    }

    public function getProblemData($categoryId, $userId, $answeredProblems)
    {
        $problem =  Problem::with('problemOptions')
            ->when(isset($categoryId) && count($categoryId) > 0, function ($query) use ($categoryId) {
                $query->whereIn('category_id', $categoryId);
            })->when(isset($userId), function ($query) use ($userId) {
                //get only problems that user haven't been answered
                $userAnsweredProblems = Answer::where('user_id', $userId)->pluck('problem_id')->toArray();
                $query->whereNotIn('id', $userAnsweredProblems);
            })->when(count($answeredProblems) > 0 && !isset($userId), function ($query) use ($answeredProblems) {
                //when user id is null,check answeredProblems and only get problems that have not in answeredProblems array
                $answeredProblemsId = $answeredProblems->pluck('problem_id')->toArray();
                $query->whereNotIn('id', $answeredProblemsId);
            })
            ->inRandomOrder()->first();
        // $imageUrl = asset('/storage/problems/' . $problem->content_image_url);
        // $problem->image_url = $imageUrl;
        return $problem;
    }


}
