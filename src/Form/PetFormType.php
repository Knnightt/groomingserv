<?php

namespace App\Form;

use App\Entity\Pet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Pet Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter pet name',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Pet name is required']),
                ],
            ])
            ->add('species', ChoiceType::class, [
                'label' => 'Species',
                'choices' => [
                    'Dog' => 'Dog',
                    'Cat' => 'Cat',
                    'Bird' => 'Bird',
                    'Rabbit' => 'Rabbit',
                    'Hamster' => 'Hamster',
                    'Guinea Pig' => 'Guinea Pig',
                    'Other' => 'Other',
                ],
                'placeholder' => 'Select species',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Species is required']),
                ],
            ])
            ->add('breed', TextType::class, [
                'label' => 'Breed',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter breed',
                ],
            ])
            ->add('age', NumberType::class, [
                'label' => 'Age (years)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter age in years',
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Unknown' => 'Unknown',
                ],
                'placeholder' => 'Select gender',
                'attr' => [
                    'class' => 'form-select',
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
            ->add('weight', NumberType::class, [
                'label' => 'Weight (lbs)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter weight',
                ],
            ])
            ->add('lifeStage', ChoiceType::class, [
                'label' => 'Life Stage',
                'choices' => [
                    'Puppy' => 'Puppy',
                    'Adult' => 'Adult',
                    'Senior' => 'Senior',
                ],
                'placeholder' => 'Select life stage',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('coatType', TextType::class, [
                'label' => 'Coat Type',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter coat type (e.g., short, long, curly)',
                ],
            ])
            ->add('temperament', TextType::class, [
                'label' => 'Temperament',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter temperament (e.g., calm, energetic, shy)',
                ],
            ])
            ->add('isNeutered', CheckboxType::class, [
                'label' => 'Neutered/Spayed',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => ['class' => 'form-check-label'],
            ])
            ->add('isVaccinated', CheckboxType::class, [
                'label' => 'Vaccinated',
                'required' => false,
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label_attr' => ['class' => 'form-check-label'],
            ])
            ->add('medicalNotes', TextareaType::class, [
                'label' => 'Medical Notes',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Enter any medical notes or conditions',
                ],
            ])
            ->add('allergies', TextareaType::class, [
                'label' => 'Allergies',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2,
                    'placeholder' => 'Enter any allergies',
                ],
            ])
            ->add('isActive', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Active' => true,
                    'Inactive' => false,
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
        ]);
    }
}