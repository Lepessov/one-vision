<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

///**
// * @OA\Schema(
// *     schema="Post",
// *     type="object",
// *     @OA\Property(property="id", type="integer", example=1),
// *     @OA\Property(property="title", type="string", example="Post Title"),
// *     @OA\Property(property="description", type="string", example="Post Description
// *     @OA\Property(property="tags", type="array", @OA\Items(type="string")),
// *     @OA\Property(property="reactions", type="integer", example=10),
// *     @OA\Property(property="views", type="integer", example=100),
// *     @OA\Property(property="userId", type="integer", example=1)
// * )
// *
// */
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'dummy_post_id',
    ];
}
