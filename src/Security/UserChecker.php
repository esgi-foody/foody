<?php
<<<<<<< HEAD

=======
>>>>>>> Fix conflicts
namespace App\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

<<<<<<< HEAD
=======
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

>>>>>>> Fix conflicts
class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
<<<<<<< HEAD
        if (!$user->getStatus()) {
            throw new CustomUserMessageAuthenticationException(
                'Vous devez valider votre inscription en cliquant sur le lien reçu par email.'
=======
        dump($user->getUsername());die();
        if ( $user->getStatus() == 0) {
            // or to customize the message shown
            throw new CustomUserMessageAuthenticationException(
                'Veuillez activer votre compte pour pouvoir vous connecté'
>>>>>>> Fix conflicts
            );
        }
    }

<<<<<<< HEAD
    public function checkPostAuth(UserInterface $user) {}
}
=======
    public function checkPostAuth(UserInterface $user)
    {
        // user account is expired, the user may be notified
        if ($user->getStatus() == 1) {
            throw new CustomUserMessageAuthenticationException('');
        }
    }
}
>>>>>>> Fix conflicts
