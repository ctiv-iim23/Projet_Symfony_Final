<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,['attr'=>['class'=>'form-control']])
            ->add('photo',FileType::class,['attr'=>['class'=>'form-control-file'],'data_class' => null])
            ->add('quantite',IntegerType::class,['attr'=>['class'=>'form-control-file']])
            ->add('prix',IntegerType::class,['attr'=>['class'=>'form-control-file']])
            ->add('ajouter', SubmitType::class,['attr'=>['class'=>'btn btn-dark']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
