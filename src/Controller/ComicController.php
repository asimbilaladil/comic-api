<?php


namespace App\Controller;


use App\Service\ProcessService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComicController extends AbstractController
{

    private $processService;

    public function __construct(ProcessService $processService)
    {
        $this->processService = $processService;
    }

    /**
     * @Route("/api/comics}", methods={"GET"})
     */
    public function showAll()
    {
        $data = $this->processService->processData();
        // return a JSON response
        return new JsonResponse($data, Response::HTTP_CREATED);
    }

}

