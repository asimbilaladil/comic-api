<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComicController extends AbstractController
{


    public function __construct()
    {
    }

    /**
     * @Route("/api/comics}", methods={"GET"})
     */
    public function showAll()
    {
        // return a JSON response
        return new JsonResponse(['status' => 200], Response::HTTP_CREATED);
    }

}

