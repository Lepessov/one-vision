<?php

namespace App\Repositories;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10);
    public function create(array $data): Post;
    public function update(Post $post, array $data): Post;
    public function delete(Post $post): bool;
}
