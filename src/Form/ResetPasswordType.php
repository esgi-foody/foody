<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;


class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Nouveau mot de passe'),
                'second_options' => array('label' => 'Confirmer le mot de passe'),
                'invalid_message' => 'Les mot de passes ne correspondent pas',
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 12,
                        'minMessage' => 'Le mot de passe doit contenir entre 6 et 12 caractères',
                        'maxMessage' => 'Le mot de passe doit contenir entre 6 et 12 caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
                        'message' => 'Le mot de passe doit contenir au moins 1 caractère majuscule, minuscule, 1 chiffre et peut contenir des symboles'
                    ])
                ]
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Changer mon mot de passe',
            ]);
    }

}
