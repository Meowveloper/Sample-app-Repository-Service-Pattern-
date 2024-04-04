<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProblemUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'imageUrl' => 'required',
            'category_id' => 'required',
            // 'expected_time_to_complete' => 'required',
            'option_1' => 'required|max:255',
            'option_2' => 'required|max:255',
            'option_3' => 'required|max:255',
            'option_4' => 'required|max:255',
            'correct_option_id' => 'required'
        ];
    }

    // protected function prepareForValidation()
    // {
    //     logger('Request Data:', $this->all());
    // }

    public function messages(): array
    {
        return [
            'imageUrl.required' => 'The image is required.',
            'category_id.required' => 'The selected category is required.',
            'option_1.required' => 'First answer option is required.',
            'option_1.max' => 'First answer option must not exceed :max characters.',
            'option_2.required' => 'Second answer option is required.',
            'option_2.max' => 'Second answer option must not exceed :max characters.',
            'option_3.required' => 'Third answer option is required.',
            'option_3.max' => 'Third answer option must not exceed :max characters.',
            'option_4.required' => 'Fourth answer option is required.',
            'option_4.max' => 'Fourth answer option must not exceed :max characters.',
            'correct_option_id.required' => 'The correct option is required.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ],422);
        throw new HttpResponseException($response);
    }
}
