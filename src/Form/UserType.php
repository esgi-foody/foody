<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, array(
                'label' => 'Pseudo, vous pouvez le changer à tout moment',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 30,
                        'minMessage' => 'Le pseudo doit contenir entre 3 et 30 caractères',
                        'maxMessage' => 'Le pseudo doit contenir entre 3 et 30 caractères',
                    ])
                ]
            ))
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur, ce nom ne changera pas',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'max' => 30,
                        'minMessage' => 'Le pseudo doit contenir entre 3 et 30 caractères',
                        'maxMessage' => 'Le pseudo doit contenir entre 3 et 30 caractères',
                    ])
                ],
            ])
            ->add('dateOfBirth', DateType::class, [
                'format' => 'dd-MM-yyyy',
                'label' => 'Date de naissance en format jour-mois-année',
                'widget' => 'single_text',
                'attr' => ['class' => 'datepicker'],
                'invalid_message' => 'La date de naissance doit être au format jj-mm-aaaa',
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmer le mot de passe'),
                'invalid_message' => 'Les mots de passes ne correspondent pas',
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 20,
                        'minMessage' => 'Le mot de passe doit contenir entre 6 et 20 caractères',
                        'maxMessage' => 'Le mot de passe doit contenir entre 6 et 20 caractères',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
                        'message' => 'Le mot de passe doit contenir au moins 1 caractère majuscule, minuscule, 1 chiffre et peut contenir des symboles'
                    ])
                ]
            ))
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'waves-effect waves-light btn'],
                'label' => 'S\'inscrire',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
