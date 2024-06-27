<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="UpdatePostRequest",
 *     type="object",
 *     required={"title", "body"},
 *     @OA\Property(property="title", type="string", example="Updated Post Title"),
 *     @OA\Property(property="body", type="string", example="Updated Post Body")
 * )
 */
class UpdatePostRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'string|max:255',
            'body' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'The title must be a string.',
            'body.string' => 'The body must be a string.',
        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST)
        );
    }
}
