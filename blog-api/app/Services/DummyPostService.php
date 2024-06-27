<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class DummyPostService
{
    public function getPostById($id)
    {
        $response = Http::get("https://dummyjson.com/posts/{$id}");

        if ($response->failed()) {
            throw new Exception('Failed to fetch post from external API');
        }

        return $response->json();
    }

    /**
     * @throws Exception
     */
    public function createPost($data)
    {
        $response = Http::post('https://dummyjson.com/posts/add', $data);

        if ($response->failed()) {
            throw new Exception('Failed to create post in external API');
        }

        return $response->json();
    }

    /**
     * @throws Exception
     */
    public function updatePost($id, $data)
    {
        $response = Http::put("https://dummyjson.com/posts/{$id}", $data);

        if ($response->failed()) {
            throw new Exception('Failed to update post in external API');
        }

        return $response->json();
    }
}
