<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProblemRepository;
use App\Services\ProblemService;
use Illuminate\Http\Request;

class ProblemApiController extends Controller
{
    protected $problemRepository;
    protected $problemService;
    
    public function __construct(ProblemRepository $problemRepository, ProblemService $problemService)
    {
       $this->problemRepository = $problemRepository;
       $this->problemService = $problemService;
    }

    public function getAllProblem(Request $request)
    {
        $categoryId = json_decode($request->category_id);
        $answeredProblems = collect(json_decode($request->answered_problems))->map(function ($query) {
            return json_decode($query);
        });
        $userId = $request->user_id;
        $problems = $this->problemRepository->getProblemData($categoryId, $userId, $answeredProblems);
        return response()->json(
            [
                'data' => $problems,
                'status' => 'success',
                'message' => 'Successfully get all problem'
            ],
            200
        );
    }
    
    public function saveProblemWithAnswers(Request $request)
    {
        $data = $request->all();
        $problem = $this->problemService->saveProblemData($data);
        return response()->json(
            [
                'data' => $problem,
                'status' => 'success',
                'message' => 'Successfully save problem data.'
            ],
            200
        );
    }
}
