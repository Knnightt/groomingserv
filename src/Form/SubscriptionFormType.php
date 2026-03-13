<?php

namespace App\Form;

use App\Entity\Subscription;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SubscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'label' => 'User',
                'class' => User::class,
                'choice_label' => 'email',
                'placeholder' => 'Select user',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'User is required']),
                ],
            ])
            ->add('plan', ChoiceType::class, [
                'label' => 'Plan',
                'choices' => [
                    'Basic' => 'basic',
                    'Premium' => 'premium',
                    'Pro' => 'pro',
                ],
                'placeholder' => 'Select plan',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Plan is required']),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Active' => 'active',
                    'Inactive' => 'inactive',
                    'Cancelled' => 'cancelled',
                    'Trialing' => 'trialing',
                ],
                'placeholder' => 'Select status',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Status is required']),
                ],
            ])
            ->add('amount', MoneyType::class, [
                'label' => 'Amount',
                'currency' => 'USD',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter amount',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Amount is required']),
                ],
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Start Date',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control datepicker',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Start date is required']),
                ],
            ])
            ->add('endDate', DateTimeType::class, [
                'label' => 'End Date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control datepicker',
                ],
            ])
            ->add('renewalDate', DateTimeType::class, [
                'label' => 'Renewal Date',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control datepicker',
                ],
            ])
            ->add('stripeCustomerId', TextType::class, [
                'label' => 'Stripe Customer ID',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Stripe customer ID',
                ],
            ])
            ->add('stripeSubscriptionId', TextType::class, [
                'label' => 'Stripe Subscription ID',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Stripe subscription ID',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}