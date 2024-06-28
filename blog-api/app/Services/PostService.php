<?php

namespace App\Services;

use App\Gateways\DummyPostApiGatewayInterface;
use App\Models\Post;
use App\Repositories\PostRepositoryInterface;

class PostService implements PostServiceInterface
{
    protected PostRepositoryInterface $postRepository;
    protected DummyPostApiGatewayInterface $dummyPostService;

    public function __construct(PostRepositoryInterface $postRepository, DummyPostApiGatewayInterface $dummyPostService)
    {
        $this->postRepository = $postRepository;
        $this->dummyPostService = $dummyPostService;
    }

    public function getAllPosts()
    {
        $posts = $this->postRepository->getAllPaginated();
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

        return $posts;
    }

    public function createPost(array $data): Post
    {
        $dummyPost = $this->dummyPostService->createPost($data);
        return $this->postRepository->create([
            'user_id' => auth()->id(),
            'dummy_post_id' => $dummyPost['id'],
        ]);
    }

    public function updatePost(Post $post, array $data): Post
    {
        $dummyPost = $this->dummyPostService->updatePost($post->id, $data);
        return $this->postRepository->update($post, ['dummy_post_id' => $dummyPost['id']]);
    }

    public function deletePost(Post $post): bool
    {
        return $this->postRepository->delete($post);
    }
}

