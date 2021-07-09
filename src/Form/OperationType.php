<?php

namespace App\Form;

use App\Entity\Operation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('label', null, [
                "label" => "Intitulé de l'opération",
                "attr" => ["class" => "mx-3"],
            ])
            ->add('operation_amount', null, [
                "label" => "Montant en euros",
                "attr" => ["class" => "mx-3"],
            ])

            ->add('enregistrer', SubmitType::class, [
                "attr" => ["class" => "btn btn-gold mt-3"],
                'row_attr' => ['class' => 'text-start']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
