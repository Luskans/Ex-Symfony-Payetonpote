<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Payment;
use App\Form\ParticipantType as FormParticipantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, [
                'label' => 'Votre participation',
                'attr' => [
                    'id' => 'mount',
                    'class' => 'validate'
                ],
                'constraints' => [
                    new Positive([
                        'message' => 'Le montant doit être positif.'
                    ])
                ]
            ])
            ->add('participant', ParticipantType::class)
            ->add('isHidden', CheckboxType::class, [
                'label' => 'Masquer le montant de ma participation auprès des autres participants',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
