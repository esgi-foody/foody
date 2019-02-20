<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user->getStatus()) {
            throw new CustomUserMessageAuthenticationException(
                'Vous devez valider votre inscription en cliquant sur le lien reçu par email.'
            );
        }
    }

    public function checkPostAuth(UserInterface $user) {}
}
