<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Services\DummyPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Blog API Documentation",
 *      description="API documentation for the Blog API",
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Enter token in format (Bearer <token>)"
 * )
 */
class PostController extends Controller
{
    protected DummyPostService $dummyPostService;

    public function __construct(DummyPostService $dummyPostService)
    {
        $this->dummyPostService = $dummyPostService;
    }

    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Get list of posts",
     *     description="Returns list of posts",
     *     security={{"Bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $posts = Post::paginate(10);

        $posts->getCollection()->transform(function ($post) {
            $dummyData = $this->dummyPostService->getPostById($post->id);

            return [
                'id' => $post->id,
                'title' => $dummyData['title'],
                'description' => substr($dummyData['body'], 0, 128),
                'tags' => $dummyData['tags'],
                'reactions' => $dummyData['reactions'],
                'views' => $dummyData['views'],
                'userId' => $dummyData['userId'],
            ];
        });

        return response()->json($posts);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Create a new post",
     *     description="Creates a new post",
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StorePostRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     * @throws Exception
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $payload = array_merge($request->all(), ['userId' => Auth::id()]);

        $dummyPost = $this->dummyPostService->createPost($payload);

        $post = Post::create([
            'user_id' => Auth::id(),
            'dummy_post_id' => $dummyPost['id'],
        ]);

        return response()->json($post, Response::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Update an existing post",
     *     description="Updates an existing post",
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdatePostRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     * @throws Exception
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        if (Gate::denies('update-post', $post)) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $dummyPost = $this->dummyPostService->updatePost($post->id, $request->validated());

        $post->update([
            'dummy_post_id' => $dummyPost['id'],
        ]);

        return response()->json([
            'title' => $dummyPost['title'],
            'body' => $dummyPost['body'],
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Delete a post",
     *     description="Deletes a post",
     *     security={{"Bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Post deleted")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post not found"
     *     )
     * )
     */
    public function destroy(Post $post): JsonResponse
    {
        if (Gate::denies('delete-post', $post)) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
