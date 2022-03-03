<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectCommanderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'email',
                'label'=>'Пользователь'

            ])
            ->add('getRole', ChoiceType::class,[

                'choices'=>[
                    'Пользователь' => 'ROLE_USER',
                    'Командир' => 'ROLE_COMMANDER',
                ],
                'label'=>'Дать роль'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}