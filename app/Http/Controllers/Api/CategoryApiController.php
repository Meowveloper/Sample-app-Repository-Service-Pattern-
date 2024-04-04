<?php

namespace App\Http\Controllers\Api;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;


class CategoryApiController extends Controller
{

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
       $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategory(Request $request)
    {
        logger($request->all());
        $categories = $this->categoryRepository->getAllCategory($request);
        return response()->json(
            [
                'data' => $categories,
                'status' => 'success',
                'message' => 'Successfully get all category'
            ],
            200
        );
    }
}
