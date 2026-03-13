<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class ServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Service Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter service name',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Service name is required']),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Enter service description',
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
                'currency' => 'USD',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter price',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Price is required']),
                    new Positive(['message' => 'Price must be positive']),
                ],
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Duration (minutes)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter duration in minutes',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Duration is required']),
                    new Positive(['message' => 'Duration must be positive']),
                ],
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => [
                    'Basic' => 'Basic',
                    'Premium' => 'Premium',
                    'Ultimate' => 'Ultimate',
                    'Special' => 'Special',
                    'Medical' => 'Medical',
                ],
                'placeholder' => 'Select category',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('features', TextareaType::class, [
                'label' => 'Features (one per line)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Enter features, one per line',
                ],
            ])
            ->add('image', UrlType::class, [
                'label' => 'Image URL',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter image URL',
                ],
            ])
            ->add('requiresSpecialEquipment', CheckboxType::class, [
                'label' => 'Requires Special Equipment',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
            ->add('specialInstructions', TextareaType::class, [
                'label' => 'Special Instructions',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2,
                    'placeholder' => 'Enter special instructions',
                ],
            ])
            ->add('minPetAge', NumberType::class, [
                'label' => 'Minimum Pet Age (months)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter minimum age',
                ],
            ])
            ->add('maxPetAge', NumberType::class, [
                'label' => 'Maximum Pet Age (months)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter maximum age',
                ],
            ])
            ->add('weightLimit', NumberType::class, [
                'label' => 'Weight Limit (lbs)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter weight limit',
                ],
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}