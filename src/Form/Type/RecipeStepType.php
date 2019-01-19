<?php
// src/Form/Type/RecipeStepType.php
namespace App\Form\Type;

use App\Entity\RecipeStep;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class RecipeStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',TextType::class,['label' => 'Titre']);
        $builder->add('stepNumber',IntegerType::class,['label' => 'Etape n°']);
        $builder->add('content',TextareaType::class,['label' => 'Description']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecipeStep::class,
        ]);
    }
}