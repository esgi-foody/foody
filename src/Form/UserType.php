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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, array(
                'label' => 'Pseudo, vous pouvez le changer à tout moment',
                'required' => true,
                'constraints' => [new Length(['min' => 3, 'max' => 30])]
            ))
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur, ce nom ne changera pas',
                'required' => true,
                'constraints' => [new Length(['min' => 3, 'max' => 30])],
            ])
            ->add('dateOfBirth', DateType::class, [
                'format' => 'ddMMyyyy',
                'label' => 'Date de naissance en format jour/mois/année',
                'years' => range(date('Y')-100, date('Y')+100),
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmer le mot de passe'),
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
