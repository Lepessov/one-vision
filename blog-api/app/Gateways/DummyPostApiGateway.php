<?php

namespace App\Gateways;

use Exception;
use Illuminate\Support\Facades\Http;

class DummyPostApiGateway implements DummyPostApiGatewayInterface
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.dummyjson.base_url');
    }

    public function getPostById(int $id): array
    {
        $response = Http::get("{$this->baseUrl}/posts/{$id}");

        if ($response->failed()) {
            throw new Exception('Failed to fetch post data');
        }

        return $response->json();
    }

    /**
     * @throws Exception
     */
    public function createPost(array $data): array
    {
        $response = Http::post("{$this->baseUrl}/posts/add", $data);

        if ($response->failed()) {
            throw new Exception('Failed to create post');
        }

        return $response->json();
    }

    /**
     * @throws Exception
     */
    public function updatePost(int $id, array $data): array
    {
        $response = Http::put("{$this->baseUrl}/posts/{$id}", $data);

        if ($response->failed()) {
            throw new Exception('Failed to update post');
        }

        return $response->json();
    }
}
