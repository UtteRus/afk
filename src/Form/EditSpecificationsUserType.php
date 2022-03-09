<?php

namespace App\Form;

use App\Entity\Specifications;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class EditSpecificationsUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('evolution', ChoiceType::class,[
                'label'=>'Развитие персонажа',
                'choices'=>[
                    'Элитный'=>'Элитный',
                    'Элитный+'=>'Элитный+',
                    'Легендарный'=>'Легендарный',
                    'Легендарный+'=>'Легендарный+',
                    'Мифический'=>'Мифический',
                    'Мифический+'=>'Мифический+',
                    'Белый'=>'Белый',
                    '1 звезда'=>'1 звезда',
                    '2 звезды'=>'2 звезды',
                    '3 звезды'=>'3 звезды',
                    '4 звезды'=>'4 звезды',
                    '5 звезд'=>'5 звезд'
                ]
            ])
            ->add('ip',IntegerType::class, [
                'constraints'=>[

                    new Range(['max'=>40, 'maxMessage'=>'Максимальный ИП 40']),
                    new Range(['min'=>0, 'minMessage'=>'Минимальный ИП 0'])
                ],
                'label' => 'ИП'
            ])
            ->add('furniture',IntegerType::class,[
                'constraints'=>[

                    new Length(['max'=>1, 'maxMessage'=>'Максимальное количество символов 1']),
                    new Range(['max'=>9, 'maxMessage'=>'Максимальная мебель 9']),
                    new Range(['min'=>0, 'minMessage'=>'Минимально мебели 0'])
                    ],
                'label'=> 'Мебель'
            ])
            ->add('engraving', IntegerType::class,[
                'constraints'=>[

                    new Range(['max'=>90, 'maxMessage'=>'Максимальная гравировка 90']),
                    new Range(['min'=>0, 'minMessage'=>'Минимальная гравировка 0'])],
                'label'=> 'Гравировка'
            ])->add('save', SubmitType::class,[
                'label'=>'Сохранить'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Specifications::class,
        ]);
    }
}
