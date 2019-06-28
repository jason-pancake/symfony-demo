<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\Todo;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class TodoVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        if(!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if(!$subject instanceof Todo) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if($this->security->isGranted('ROLE_ADMINISTRATOR')) {
            return true;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Todo $post */
        $todo = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($todo, $user);
            case self::EDIT:
                return $this->canEdit($todo, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Todo $todo, User $user)
    {
        if ($this->canEdit($todo, $user)) {
            return true;
        }

        return false;
    }

    private function canEdit(Todo $todo, User $user)
    {
        return $user === $todo->getOwner();
    }
}
