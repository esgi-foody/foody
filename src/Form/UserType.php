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
use Symfony\Component\Validator\Constraints\DateValidator;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Ce nom sera votre nom public',
                ),
                'required' => true,
                'constraints' => [new Length(['min' => 3, 'max' => 30])]
            ))
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'exemple@gmail.com',
                ],
                'required' => true,
            ])
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'ex: @foody',
                ],
                'label' => 'Nom d\'utilisateur',
                'required' => true,
                'constraints' => [new Length(['min' => 3, 'max' => 30])]
            ])
            ->add('dateOfBirth', DateType::class, [
                'format' => 'ddMMyyyy',
                'label' => 'Date de naissance'
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmer le mot de passe'),
            ))
            ->add('S\'inscrire', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
