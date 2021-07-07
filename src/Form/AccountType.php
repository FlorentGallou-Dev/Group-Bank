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
            
            ->add('accountType', ChoiceType::class, [
                'choices' => [
                    'Compte courant' => 1,
                    'Livret A' => 2,
                    'PEL' => 3,
                ]])
            ->add('amount', null, [
                "label" => "Montant",
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
            'data_class' => Account::class,
        ]);
    }
}
