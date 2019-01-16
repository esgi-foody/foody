<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class QuillType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'quill';
    }

    public function getParent()
    {
        return TextareaType::class;
    }
}
