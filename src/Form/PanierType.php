<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddprodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite',IntegerType::class,['attr'=>['class'=>'form-control']])
            ->add('ajouter',SubmitType::class,['attr'=>['class'=>'btn btn-dark']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
