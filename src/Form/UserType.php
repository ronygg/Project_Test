<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Apellidos',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'Usuario',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ]);

        if (!$options['exclude_role']) {
            $builder
                ->add('role', EntityType::class, [
                    'class' => Role::class,
                    'label' => 'Rol',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'choice_label' => 'name',
                    'required' => true,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('r')
                            ->where('r.name != :exclude_role')
                            ->setParameter('exclude_role', 'ROLE_ADMIN')
                            ->orderBy('r.name', 'ASC');
                    },
                    'attr' => [
                        'class' => 'form-select',
                    ]
                ]);
        }

        if ($options['include_password']) {
            $builder
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'mapped' => false,
                    'required' => true,
                    'label' => false,
                    'first_options' => [
                        'label' => 'Contraseña',
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'include_password' => true,
            'exclude_role' => false,
        ]);
    }
}
