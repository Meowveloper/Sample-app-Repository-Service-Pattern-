<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
     protected $categoryRepository;
     protected $categoryService;

     public function __construct(CategoryRepository $categoryRepository, CategoryService $categoryService)
     {
         $this->categoryRepository = $categoryRepository;
         $this->categoryService    = $categoryService;
     }


    public function index()
    {

        return view('vue-pages.category.browse');
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
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $category = $this->categoryService->saveCategoryData($data);

        return response()->json(['status' => 'success','message'=>'Successfully created category', 'data' => $category]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request,$id)
    {
        $data = $request->all();

        $this->categoryService->updateCategoryData($data,$id);

        return response()->json(['status' => 'success', 'message' => 'Successfully updated category']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = $request->all();
        $this->categoryService->deleteCategoryData($data);
        return response()->json(['status' => 'success','message' => 'Successfully deleted category']);

    }

    public function getCategoryData(Request $request)
    {
        $search = $request->search;
        $data = $this->categoryRepository->getAllCategoryData($search);
        return response()->json([
            'data' => $data,
            'message' => 'Successfully get city table data',
            'status' => 'success'
        ], 200);
    }

    public function getAllCategories(){
        $categories = $this->categoryRepository->getAllCategories();
        return response()->json([
            'data'=>$categories,
            'message'=>'Successfully get categories',
            'status'=>'success'
        ], 200);
    }


}
