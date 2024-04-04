<?php

namespace App\Services;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function saveCategoryData($data)
    {
        
        return Category::create([
            'name' => $data['name'],
            'image_url' => null
        ]);
    }

    public function updateCategoryData($data,$id){
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $data['name']
        ]);

        return $category;
    }

    public function deleteCategoryData($id)
    {
        $category = Category::with('problems.problemOptions')->where('id',$id)->first();

        if ($category) {
            try {
                // Retrieve all related problems
                $problems = $category->problems;

                // Delete related problemOptions
                $problems->each(function ($problem) {
                    //set correct option id null to avoid errors when deleting options
                    $problem->update(['correct_option_id'=>null]);
                    $problem->problemOptions()->delete();
                    //delete problem photo from stroage
                    $filePath = 'public/problems/' . $problem->content_image_url;
                    Storage::delete($filePath);
                });

                // Delete the problems
                $category->problems()->delete();

                // Finally, delete the category
                $category->delete();

                // Log success or any other information
                Log::info('Category and related records deleted successfully.');
            } catch (\Exception $e) {
                // Log the exception for debugging purposes
                Log::error('Error deleting category and related records: ' . $e->getMessage());
            }
        } else {
            // Log that the category was not found
            Log::warning('Category with ID ' . $id . ' not found.');
        }

        return $category;
    }
}
