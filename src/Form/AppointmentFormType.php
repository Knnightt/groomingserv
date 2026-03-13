<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Pet;
use App\Entity\Service;
use App\Entity\Staff;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AppointmentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('startAt', DateTimeType::class, [
                'label' => 'Start Time',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control datetimepicker',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Start time is required']),
                ],
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'End Time',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control datetimepicker',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'End time is required']),
                ],
            ]);

        // Only show customer field for admin/manager/staff
        if ($user && ($user->isAdmin() || $user->isManager() || $user->isStaffMember())) {
            $builder->add('customer', EntityType::class, [
                'label' => 'Customer',
                'class' => User::class,
                'choice_label' => 'email',
                'placeholder' => 'Select customer',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Customer is required']),
                ],
            ]);
        }

        $builder
            ->add('pet', EntityType::class, [
                'label' => 'Pet',
                'class' => Pet::class,
                'choice_label' => 'name',
                'placeholder' => 'Select pet',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Pet is required']),
                ],
            ])
            ->add('service', EntityType::class, [
                'label' => 'Service',
                'class' => Service::class,
                'choice_label' => 'name',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('s')
                        ->where('s.isActive = :active')
                        ->setParameter('active', true)
                        ->orderBy('s.name', 'ASC');
                },
                'placeholder' => 'Select service',
                'attr' => [
                    'class' => 'form-select',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Service is required']),
                ],
            ])
            ->add('assignedStaff', EntityType::class, [
                'label' => 'Assigned Staff',
                'class' => Staff::class,
                'choice_label' => function (Staff $staff) {
                    return $staff->getDisplayName() . ' (' . $staff->getRoleLabel() . ')';
                },
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('s')
                        ->where('s.employmentStatus = :active')
                        ->setParameter('active', 'Active')
                        ->orderBy('s.staffRole', 'ASC');
                },
                'required' => false,
                'placeholder' => 'Select staff (optional)',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Pending' => 'Pending',
                    'Confirmed' => 'Confirmed',
                    'Completed' => 'Completed',
                    'Cancelled' => 'Cancelled',
                    'No Show' => 'No Show',
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
            ->add('discount', MoneyType::class, [
                'label' => 'Discount',
                'currency' => 'USD',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter discount amount',
                ],
            ])
            ->add('tax', MoneyType::class, [
                'label' => 'Tax',
                'currency' => 'USD',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter tax amount',
                ],
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Payment Method',
                'choices' => [
                    'Cash' => 'Cash',
                    'Credit Card' => 'Credit Card',
                    'Debit Card' => 'Debit Card',
                    'Online Payment' => 'Online Payment',
                    'Check' => 'Check',
                ],
                'required' => false,
                'placeholder' => 'Select payment method',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('isPaid', ChoiceType::class, [
                'label' => 'Payment Status',
                'choices' => [
                    'Paid' => true,
                    'Not Paid' => false,
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Enter any notes',
                ],
            ])
            ->add('groomerNotes', TextareaType::class, [
                'label' => 'Groomer Notes',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 2,
                    'placeholder' => 'Enter groomer notes',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
            'user' => null,
        ]);
        
        $resolver->setAllowedTypes('user', ['null', User::class]);
    }
}