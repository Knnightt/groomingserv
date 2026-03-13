<?php

namespace App\Form;

use App\Entity\Staff;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class StaffFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'label' => 'User Account',
                'class' => User::class,
                'choice_label' => 'email',
                'placeholder' => 'Select user account',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'User account is required']),
                ],
            ])
            ->add('staffId', TextType::class, [
                'label' => 'Staff ID',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter staff ID',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Staff ID is required']),
                ],
            ])
            ->add('staffRole', ChoiceType::class, [
                'label' => 'Staff Role',
                'choices' => [
                    'Groomer' => Staff::ROLE_GROOMER,
                    'Receptionist' => Staff::ROLE_RECEPTIONIST,
                    'Manager' => Staff::ROLE_MANAGER,
                    'Veterinarian' => Staff::ROLE_VETERINARIAN,
                ],
                'placeholder' => 'Select role',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Staff role is required']),
                ],
            ])
            ->add('photo', UrlType::class, [
                'label' => 'Photo URL',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter photo URL',
                ],
            ])
            ->add('biography', TextareaType::class, [
                'label' => 'Biography',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Enter biography',
                ],
            ])
            ->add('experienceYears', NumberType::class, [
                'label' => 'Years of Experience',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter years of experience',
                ],
            ])
            ->add('hourlyRate', MoneyType::class, [
                'label' => 'Hourly Rate',
                'currency' => 'USD',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter hourly rate',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Hourly rate is required']),
                ],
            ])
            ->add('hireDate', DateTimeType::class, [
                'label' => 'Hire Date',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control datepicker',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Hire date is required']),
                ],
            ])
            ->add('employmentStatus', ChoiceType::class, [
                'label' => 'Employment Status',
                'choices' => [
                    'Active' => Staff::STATUS_ACTIVE,
                    'On Leave' => Staff::STATUS_ON_LEAVE,
                    'Inactive' => Staff::STATUS_INACTIVE,
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Employment status is required']),
                ],
            ])
            ->add('specializations', CollectionType::class, [
                'label' => 'Specializations',
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => ['class' => 'form-control mb-2'],
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'required' => false,
                'attr' => [
                    'class' => 'specializations-collection',
                ],
            ])
            ->add('workingDays', ChoiceType::class, [
                'label' => 'Working Days',
                'choices' => [
                    'Monday' => 'Monday',
                    'Tuesday' => 'Tuesday',
                    'Wednesday' => 'Wednesday',
                    'Thursday' => 'Thursday',
                    'Friday' => 'Friday',
                    'Saturday' => 'Saturday',
                    'Sunday' => 'Sunday',
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('startTime', TimeType::class, [
                'label' => 'Start Time',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control timepicker',
                ],
            ])
            ->add('endTime', TimeType::class, [
                'label' => 'End Time',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control timepicker',
                ],
            ])
            ->add('canHandleAggressivePets', CheckboxType::class, [
                'label' => 'Can Handle Aggressive Pets',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('isCertified', CheckboxType::class, [
                'label' => 'Certified',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('certifications', TextareaType::class, [
                'label' => 'Certifications',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2,
                    'placeholder' => 'Enter certifications',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Staff::class,
        ]);
    }
}