<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;


class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('pseudo', TextType::class, [
                'label' => "Pseudo",
                'constraints' => [new Length(['min' => 3, 'max' => 30])]
            ])

            ->add('biography',CKEditorType::class,[
                'label' => "Biographie"
            ])


            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => false,
                'download_label' => false,
                'label' => 'Image de profil'
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
