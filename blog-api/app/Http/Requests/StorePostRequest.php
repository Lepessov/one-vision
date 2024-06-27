<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="StorePostRequest",
 *     type="object",
 *     required={"title", "body"},
 *     @OA\Property(property="title", type="string", example="Post Title"),
 *     @OA\Property(property="body", type="string", example="Post Body")
 * )
 */
class StorePostRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'body.required' => 'The body field is required.',
        ];
    }

    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST)
        );
    }
}
