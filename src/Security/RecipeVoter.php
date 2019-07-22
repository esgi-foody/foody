<?php

namespace App\Security;

use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RecipeVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Recipe) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $recipe = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($recipe, $user);
            case self::DELETE:
                return $this->canDelete($recipe, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Recipe $recipe, User $user)
    {
        return $user === $recipe->getUserRecipe();
    }

    private function canDelete(Recipe $recipe, User $user)
    {
        return $user === $recipe->getUserRecipe();
    }
}
