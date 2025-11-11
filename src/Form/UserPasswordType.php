<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Usuario',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'disabled' => true,
            ])
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Password',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'mapped' => false,
                'constraints' => [
                    new Length(
                        min: 8,
                        minMessage: 'La contraseña introducida debe
                        tener al menos 8 caracteres.',
                    )
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => true,
                'label' => false,
                'first_options' => [
                    'label' => 'Nueva Contraseña',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ],
                'second_options' => [
                    'label' => 'Repetir Contraseña',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ],
                'invalid_message' => 'Las contraseñas no coinciden.',
                'constraints' => [
                    new Length(
                        min: 8,
                        minMessage: 'La contraseña introducida debe
                        tener al menos 8 caracteres.',
                    )
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
