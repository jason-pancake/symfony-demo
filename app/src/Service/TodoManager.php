<?php

namespace App\Service;

use App\Entity\Todo;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Class TodoManager
 * @package App\Service
 * @author Jason Pancake <jason.pancake@gmail.com>
 */
class TodoManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $objectManager;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $objectManager
     */
    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return object[]
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param User $user
     * @return object[]
     */
    public function findByOwner(User $user)
    {
        return $this->getRepository()->findBy([
            'owner' => $user,
        ]);
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository(Todo::class);
    }

    /**
     * @return Todo
     */
    public function createTodo(): Todo
    {
        return new Todo();
    }

    /**
     * @return Todo
     */
    public function createTodoForOwner(User $user): Todo
    {
        return $this->createTodo()
            ->setOwner($user)
        ;
    }

    /**
     * @param Todo $todo
     * @param bool $andFlush
     * @return Todo
     */
    public function updateTodo(Todo $todo, bool $andFlush = true): Todo
    {
        $this->objectManager->persist($todo);

        if ($andFlush) {
            $this->objectManager->flush();
        }

        return $todo;
    }
}
