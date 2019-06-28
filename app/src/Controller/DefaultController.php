<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET","POST"})
     * @return Response
     */
    public function login(): Response
    {
        return $this->render('default/index.html.twig');
    }
}

