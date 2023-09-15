<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('campaign')
            ->add('name', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'id' => 'name',
                    'class' => 'validate'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre Email',
                'attr' => [
                    'id' => 'email',
                    'class' => 'validate'
                ]
            ])
            ->add('payments', CollectionType::class, [
                'entry_type' => PaymentType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,               
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            // 'data_class' => Payment::class
        ]);
    }
}
