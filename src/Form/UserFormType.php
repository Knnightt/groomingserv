<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('username', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => $options['is_new'],
                'attr' => ['class' => 'form-control'],
                'constraints' => $options['is_new'] ? [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ] : [],
                'help' => $options['is_new'] ? 'Password must be at least 6 characters' : 'Leave blank to keep current password',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Staff' => 'ROLE_STAFF',
                    'Manager' => 'ROLE_MANAGER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label'],
            ])
            ->add('isVerified', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true,
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label'],
            ])
            ->add('isActive', ChoiceType::class, [
                'choices' => [
                    'Active' => true,
                    'Inactive' => false,
                ],
                'expanded' => true,
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label'],
            ])
            
            // UserProfile fields (mapped: false because they're not directly on User entity)
            ->add('fullName', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Full Name',
                'data' => $options['data']?->getUserProfile()?->getFullName(),
            ])
            ->add('phoneNumber', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Phone Number',
                'data' => $options['data']?->getUserProfile()?->getPhoneNumber(),
            ])
            ->add('gender', ChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Select Gender' => null,
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Other' => 'Other',
                ],
                'attr' => ['class' => 'form-select'],
                'label' => 'Gender',
                'data' => $options['data']?->getUserProfile()?->getGender(),
            ])
            ->add('dateOfBirth', DateType::class, [
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'label' => 'Date of Birth',
                'data' => $options['data']?->getUserProfile()?->getDateOfBirth(),
            ])
            ->add('address', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 2],
                'label' => 'Address',
                'data' => $options['data']?->getUserProfile()?->getAddress(),
            ])
            ->add('city', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'City',
                'data' => $options['data']?->getUserProfile()?->getCity(),
            ])
            ->add('state', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'State',
                'data' => $options['data']?->getUserProfile()?->getState(),
            ])
            ->add('zipCode', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'ZIP Code',
                'data' => $options['data']?->getUserProfile()?->getZipCode(),
            ])
            ->add('country', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'label' => 'Country',
                'data' => $options['data']?->getUserProfile()?->getCountry(),
            ])
            ->add('bio', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3],
                'label' => 'Bio',
                'data' => $options['data']?->getUserProfile()?->getBio(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_new' => false,
        ]);
    }
}