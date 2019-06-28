<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit } characters long',
                        'max' => 4096
                    ])
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrator' => 'ROLE_ADMINISTRATOR',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('create', SubmitType::class)
        ;
    }
}