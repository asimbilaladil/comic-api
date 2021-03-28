<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ProcessService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ComicController extends AbstractController
{

    private $processService;

    public function __construct(ProcessService $processService)
    {
        $this->processService = $processService;
    }

    public function showAll(): JsonResponse
    {
        $data = $this->processService->processData();
        return new JsonResponse([
            "data" => $data,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }
}

