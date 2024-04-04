<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAllCategoriesCount(){
        return Category::count();
    }

   public function getAllCategory($request)
   {
        $perPage = 4;

        $page = $request->input('page',1);
        $offset = ($page -1) * $perPage;


        $categories = Category::skip($offset)->take($perPage)->get();
       
        logger($categories);
        return $categories;
   }

   // admin panel web-api.php
   public function getAllCategories()
    {
        $categories = Category::orderBy('id','desc')->get();
        return $categories;
    }

    public function getAllCategoryData($search)
    {
        return Category::when(isset($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orderBy('id','desc')->get();
    }
}
