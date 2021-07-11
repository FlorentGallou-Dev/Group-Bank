<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add(
                'accountType',
                ChoiceType::class,
                [
                    'label' => "Type de comptes",
                    'choices' => [
                        'Compte courant' => 'Compte-courant',
                        'Livret A' => 'Livret-A',
                        'PEL' => 'PEL'
                    ],
                    "attr" => ["class" => "mx-3"],
            ])
            
            ->add('amount', null, [
                "label" => "Montant",
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
            'data_class' => Account::class,
        ]);
    }
}