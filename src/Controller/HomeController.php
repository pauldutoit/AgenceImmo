<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PropertyRepository;

class HomeController extends Controller {

    /**
     * @Route("/", name="home")
     * @return Response
     */

    public function index(PropertyRepository $repository) :Response
    {
        $properties = $repository->findLatest();
        return $this->render('home.html.twig',[
            'properties' => $properties
        ]);
    }



}