<?php

namespace App\Controller;

use App\Form\TodoType;
use App\Entity\Todo;
use App\Security\TodoVoter;
use App\Service\TodoManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends AbstractController
{
    /**
     * @Route("/todos", name="todos", methods={"GET"})
     * @param TodoManager $todoManager
     * @return Response
     */
    public function index(TodoManager $todoManager): Response
    {
        if($this->isGranted('ROLE_ADMINISTRATOR')) {
            $todos = $todoManager->findAll();
        } else {
            $todos = $todoManager->findByOwner($this->getUser());
        }

        return $this->render('todos/index.html.twig', [
            'todos' => $todos,
        ]);
    }

    /**
     * @Route("/todos/create", name="create_todo", methods={"GET", "POST"})
     * @param Request $request
     * @param TodoManager $todoManager
     * @return Response
     */
    public function create(Request $request, TodoManager $todoManager)
    {
        $todo = $todoManager->createTodoForOwner($this->getUser());

        $form = $this->createForm(TodoType::class, $todo);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $todoManager->updateTodo($todo);

            $this->addFlash('success', 'Todo created!');
            return $this->redirectToRoute('todos');
        }

        return $this->render('todos/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/todos/{id}", name="todo", methods={"GET"})
     * @param Todo $todo
     * @return Response
     */
    public function show(Todo $todo): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VIEW, $todo);
        return $this->render('todos/show.html.twig', [
            'todo' => $todo
        ]);
    }
}