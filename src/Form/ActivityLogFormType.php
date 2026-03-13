<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityLogFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'label' => 'User',
                'class' => User::class,
                'choice_label' => 'email',
                'required' => false,
                'placeholder' => 'All Users',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('action', TextType::class, [
                'label' => 'Action',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Filter by action',
                ],
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control datepicker',
                ],
            ])
            ->add('endDate', DateType::class, [
                'label' => 'End Date',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'class' => 'form-control datepicker',
                ],
            ])
            ->add('sortBy', ChoiceType::class, [
                'label' => 'Sort By',
                'choices' => [
                    'Newest First' => 'createdAt_desc',
                    'Oldest First' => 'createdAt_asc',
                    'User A-Z' => 'user_asc',
                    'User Z-A' => 'user_desc',
                ],
                'data' => 'createdAt_desc',
                'attr' => [
                    'class' => 'form-select',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // This form doesn't map to an entity
        ]);
    }
}