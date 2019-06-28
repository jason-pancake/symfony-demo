<?php

namespace App\Controller;

use App\Form\UserType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UserManager;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users", methods={"GET"})
     * @param UserManager $userManager
     * @return Response
     */
    public function index(UserManager $userManager): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $userManager->findAll()
        ]);
    }

    /**
     * @Route("/users/create", name="create_user", methods={"GET", "POST"})
     * @param Request $request
     * @param UserManager $userManager
     * @return Response
     */
    public function create(Request $request, UserManager $userManager)
    {
        $user = $userManager->createUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $userManager->updateUser($user);

            $this->addFlash('success', 'User created!');
            return $this->redirectToRoute('users');
        }

        return $this->render('users/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}