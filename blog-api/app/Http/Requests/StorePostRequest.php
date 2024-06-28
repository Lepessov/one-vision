<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(
 *     schema="StorePostRequest",
 *     required={"title", "body"},
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the post"
 *     ),
 *     @OA\Property(
 *         property="body",
 *         type="string",
 *         description="Body content of the post"
 *     )
 * )
 */
class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST)
        );
    }
}
