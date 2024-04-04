<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnswerService;
use Illuminate\Http\Request;

class AnswerApiController extends Controller
{
    protected $answerService;
    
    public function __construct(AnswerService $answerService)
    {
       $this->answerService = $answerService;
    }
    
    public function saveUserAnswer(Request $request)
    {
        $data = $request->all();
        $answer = $this->answerService->saveAnswer($data);
        return response()->json(
            [
                'data' => $answer,
                'status' => 'success',
                'message' => 'Successfully save answered data.'
            ],200
        );
    }
}
