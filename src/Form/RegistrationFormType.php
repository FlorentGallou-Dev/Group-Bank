<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', null,[
                'label' => "Nom de famille",
                "attr" => ["class" => "mx-3"],
                ])
            ->add('firstname', null,[
                'label' => "Prénom",
                "attr" => ["class" => "mx-3"],
                ])
            ->add('email', null,[
                'label' => "Email",
                "attr" => ["class" => "mx-3"],
                ])
            ->add('sex', ChoiceType::class, [
                'label' => "Sexe",
                'choices'  => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Other' => 'Other',
                ],
                "attr" => ["class" => "mx-3"],
            ])
            ->add('birthdate', DateType::class, [
                'label' => "Date de naissance",
                'format' => 'dd MM yyyy',
                'placeholder' => [
                    'day' => 'Jour', 'month' => 'Mois', 'year' => 'Année',
                ],
                "attr" => ["class" => "mx-3"],
            ])
            ->add('city', null,[
                'label' => "Ville",
                "attr" => ["class" => "mx-3"],
                ])
            ->add('city_code', null,[
                'label' => "Code Postal",
                "attr" => ["class" => "mx-3"],
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez acceptez les termes.',
                    ]),
                ],
                "attr" => ["class" => "mx-3"],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', "class" => "mx-3"],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
            'data_class' => User::class,
        ]);
    }
}
