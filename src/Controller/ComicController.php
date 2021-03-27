<?php


namespace App\Controller;


use App\Service\ProcessService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ComicController extends AbstractController
{

    private $processService;

    public function __construct(ProcessService $processService)
    {
        $this->processService = $processService;
    }

    /**
     * showAll method is used to get all comics
     *
     * @return JsonResponse
     */
    public function showAll(): JsonResponse
    {

        $data = $this->processService->processData();
        // return a JSON response
        return new JsonResponse([
            "data" => $data,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

}

