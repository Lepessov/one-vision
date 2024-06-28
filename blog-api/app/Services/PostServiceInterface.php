<?php

namespace App\Services;

use App\Models\Post;

interface PostServiceInterface
{
    public function getAllPosts();
    public function createPost(array $data): Post;
    public function updatePost(Post $post, array $data): Post;
    public function deletePost(Post $post): bool;
}
