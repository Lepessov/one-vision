<?php

namespace App\Gateways;

interface DummyPostApiGatewayInterface
{
    public function getPostById(int $id);
    public function createPost(array $data);
    public function updatePost(int $id, array $data);
}
