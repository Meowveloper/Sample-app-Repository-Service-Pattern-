<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use Illuminate\Http\Request;
use App\Services\ProblemService;
use App\Http\Requests\ProblemRequest;
use App\Repositories\ProblemRepository;
use App\Http\Requests\ProblemUpdateRequest;

class ProblemController extends Controller
{

    protected $problemRepository;
    protected $problemService;

    public function __construct(ProblemRepository $problemRepository, ProblemService $problemService)
    {
        $this->problemRepository = $problemRepository;
        $this->problemService    = $problemService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vue-pages.problem.browse');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProblemRequest $request)
    {
        $data = $request->all();
        $problem = $this->problemService->saveAdminProblemData($data);

        return response()->json(
            [
                'data' => $problem,
                'status' => 'success',
                'message' => 'Successfully save problem data.'
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Problem $problem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Problem $problem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProblemUpdateRequest $request,$id)
    {
        $data = $request->all();
        $problem = $this->problemService->updateAdminProblemData($data,$id);
        return response()->json(
            [
                'data' => $problem,
                'status' => 'success',
                'message' => 'Successfully update problem data.'
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = $request->all();
        $this->problemService->deleteProblemData($data);
        return response()->json(
            [
                'status' => 'success',
                'message' => 'Successfully delete problem data.'
            ],
            200
        );
    }

    public function getAllProblems()
    {
        $problems = $this->problemRepository->getAllProblems();

        return response()->json(['data' => $problems, 'message' => 'Successfully get problems', 'status' => 'success'], 200);
    }

    public function getProblem($id){
        $problem = $this->problemRepository->getProblem($id);

        return response()->json(['data' => $problem , 'status' => 'success'],200);
    }

    public function uploadProblemImage(Request $request)
    {
        $data = $request->all();

        $image = $this->problemService->saveProblemImage($data);

        $response = [
            'image' => $image,
            'message' => 'Successfully upload problem image',
            'status' => 'success',
        ];

        return response()->json($response);
    }


}
