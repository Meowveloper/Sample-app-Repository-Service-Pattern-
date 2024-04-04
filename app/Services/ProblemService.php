<?php

namespace App\Services;

use App\Models\Problem;
use App\Models\ProblemOption;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Storage;

class ProblemService
{
    use FileUploadTrait;

    public function saveProblemData($data)
    {
        $problem = new Problem();
        $file_name = null;

        if ($data['photo']) {
            $photo = $data['photo'];
            $file_name = $this->uploadFile($photo, 'public', 'problems');
            $imageUrl = asset('/storage/problems/' . $file_name);
        }

        $problem->content_image_url = $file_name;
        $problem->expected_time_to_complete = $data['expected_time_to_complete'];
        $problem->category_id = $data['category_id'];
        $problem->save();

        // Save problem options
        if (isset($data['problem_options'])) {
            $problemOptions = explode(',', $data['problem_options']);
            foreach ($problemOptions as $problemOptionData) {
                $problemOption = new ProblemOption();
                $problemOption->content = $problemOptionData;
                $problemOption->problem_id = $problem->id;
                $problemOption->save();
            }
        }

        $correctOptionId = ProblemOption::where('content', $data['correct_option'])->first()->id ?? null;
        $problem->correct_option_id = $correctOptionId;
        $problem->save();

        return $problem;
    }

    public function saveAdminProblemData($data)
    {

        // Create a new problem
        $problem = new Problem();
        $problem->content_image_url = $data['imageUrl'][0];
        $problem->expected_time_to_complete = null;
        $problem->category_id = $data['selectedCategory'];
        $problem->save();

        // Create problem options
        $correctOptionId = null;

        for ($i = 1; $i <= 4; $i++) {
            $optionKey = 'option_' . $i;
            $optionContent = $data[$optionKey];
            $problemOption = new ProblemOption();
            $problemOption->content = $optionContent;
            $problemOption->problem_id = $problem->id;
            $problemOption->save();

            // Check if this is the correct option and store its ID
            if ($optionContent === $data['correct_option']) {
                $correctOptionId = $problemOption->id;
            }
        }

        // Update the correct option ID in the problem
        $problem->correct_option_id = $correctOptionId;
        $problem->save();

        return $problem;
    }

    public function updateAdminProblemData($data, $id)
    {
        $problem = Problem::find($id);
        //check photo is same
        if($problem->content_image_url != $data['imageUrl'][0]) {
            //if not, delete the old photo
            $filePath = 'public/problems/' . $problem->content_image_url;
            Storage::delete($filePath);
        }
        // Update the problem
        $problem = Problem::where('id', $id)->update([
            'content_image_url' => $data['imageUrl'][0],
            'expected_time_to_complete' => null,
            'category_id' => $data['category_id'],
            'correct_option_id' => $data['correct_option_id'],
        ]);
        for ($i = 1; $i <= 4; $i++) {
            $optionKey = 'option_' . $i;
            $optionContent = $data[$optionKey];

            // Update the specific option using the loop index
            ProblemOption::where('problem_id', $id)
            ->where('id', $i) // Assuming 'id' corresponds to the option number
            ->update(['content' => $optionContent]);
        }

        return $problem;
    }

    public function deleteProblemData($data)
    {
        $problem = Problem::find($data['id']);

        if ($problem) {
            // Delete the associated problem options
            $problem->problemOptions()->delete();
            // Now delete the problem itself
            $problem->delete();
        }

        return $problem;
    }

    public function saveProblemImage($data)
    {
        $file_name = null;

        if (isset($data['files']) && is_array($data['files']) && count($data['files']) > 0) {
            $photo = $data['files'][0];

            // Assuming $this->uploadFile is accessible in ProblemService due to the use of the trait
            $file_name = $this->uploadFile($photo, 'public', 'problems');
        }
        return $file_name;
    }
}
