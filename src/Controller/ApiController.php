<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    #[Route('/api')]
    public function apiAction(): JsonResponse
    {
        return new JsonResponse([
            'status' => true,
            'message' => 'API is available'
        ]);
    }
}