<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Class UserManager
 * @package App\Service
 * @author Jason Pancake <jason.pancake@gmail.com>
 */
class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $objectManager;

    /**
     * @var PasswordUpdater
     */
    protected $passwordUpdater;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $objectManager
     * @param PasswordUpdater $passwordUpdater
     */
    public function __construct(EntityManagerInterface $objectManager, PasswordUpdater $passwordUpdater)
    {
        $this->objectManager = $objectManager;
        $this->passwordUpdater = $passwordUpdater;
    }

    /**
     * @return object[]
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->objectManager->getRepository(User::class);
    }

    /**
     * @return User
     */
    public function createUser(): User
    {
        return new User();
    }

    /**
     * @param User $user
     * @param bool $andFlush
     * @return User
     */
    public function updateUser(User $user, bool $andFlush = true): User
    {
        $this->updatePassword($user);
        $this->objectManager->persist($user);

        if ($andFlush) {
            $this->objectManager->flush();
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function updatePassword(User $user): void
    {
        $this->passwordUpdater->hashPassword($user);
    }

    /**
     * @param string $email
     * @return object|null
     */
    public function findUserByEmail(string $email)
    {
        return $this->getRepository()->findOneBy(['email' => $email]);
    }
}
