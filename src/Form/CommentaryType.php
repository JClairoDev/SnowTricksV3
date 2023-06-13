<?php

namespace App\Form;

use App\Entity\Commentary;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commentary', CKEditorType::class, [
                'config'=> [
                    'toolbar'=>'basic'
                ],
                'label'=>'Laissez un commentaire'
            ])
            ->add('envoyer', SubmitType::class,[
                'label'=>'Publier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentary::class,
        ]);
    }
}
