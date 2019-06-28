<?php

namespace App\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

/**
 * Class updating the hashed password in the user when there is a new password.
 *
 * @author Jason Pancake <jason.pancake@gmail.com>
 */
class PasswordUpdater
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * PasswordUpdater constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param User $user
     */
    public function hashPassword(User $user): void
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === strlen($plainPassword)) {
            return;
        }

        $hashedPassword = $this->userPasswordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}
