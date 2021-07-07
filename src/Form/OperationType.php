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
            // ->add('account', null, [
            //     "label" => "Choisissez votre compte",
            // ])
            ->add('account', ChoiceType::class, [
                'choices' => [
                    'Compte courant' => 1,
                    'Livret A' => 2,
                    'PEL' => 3,
                ]])
            // ->add('operation_type', null, [
            //     "label" => "Type d'opétation",
            // ])
            ->add('operationType', ChoiceType::class, [
                'choices' => [
                    'Dépôt' => 1,
                    'Retrait' => 2,
                ]])
            ->add('operation_amount', null, [
                "label" => "Montant en euros",
            ])

            ->add('enregistrer', SubmitType::class, [
                "attr" => ["class" => "bg-danger text-white"],
                'row_attr' => ['class' => 'text-center']
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
