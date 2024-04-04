<?php

namespace App\Http\Controllers;

use App\Models\ProblemOption;
use App\Repositories\ProblemOptionRepository;
use App\Services\ProblemOptionService;
use Illuminate\Http\Request;

class ProblemOptionController extends Controller
{
    protected $problemOptionRepository;
    protected $problemOptionService;

    public function __construct(ProblemOptionRepository $problemOptionRepository,ProblemOptionService $problemOptionService)
    {
        $this->problemOptionRepository = $problemOptionRepository;
        $this->problemOptionService = $problemOptionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vue-pages.problemoptions.browse');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProblemOption $problemOption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProblemOption $problemOption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $data = $request->all();
        $problemOption = $this->problemOptionService->updateProblemOption($data,$id);

        return response()->json([
            'data' => $problemOption,
            'status' => 'success',
            'message' => 'Successfully update problem option'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProblemOption $problemOption)
    {
        //
    }

    public function getAllProblemOptionsTableData(){
        $problemOptions = $this->problemOptionRepository->getProblemOptionTableData();

        return response()->json(
            [
                'data' => $problemOptions,
                'status' => 'success',
                'message' => 'Successfully get all problem options'
            ],
            200
        );
    }

    public function getProblemOptionData(Request $request){
        $search = $request->search;
        $data = $this->problemOptionRepository->getAllProblemOptionsData($search);
        return response()->json([
            'data' => $data,
            'message' => 'Successfully get city table data',
            'status' => 'success'
        ], 200);

    }

    public function getAllProblemOptions($id)
    {
        $problemOptions = $this->problemOptionRepository->getProblemOptionData($id);

        return response()->json(
            [
                'data' => $problemOptions,
                'status' => 'success',
                'message' => 'Successfully get all problem options'
            ],
            200
        );
    }
}
