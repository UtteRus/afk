<?php

namespace App\Form;


use App\Entity\Specifications;
use Doctrine\Inflector\Rules\Pattern;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class EditSpecificationsOficerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nameHero', TextType::class,[
                'property_path'=>'hid.heroName',
                'label'=>'Имя героя'
            ])
            ->add('fraction',TextType::class,[
                'property_path'=>'hid.fraction',
                'label'=>'Фракция'
            ])
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
                    new Range(['min'=>0, 'minMessage'=>'Минимальный ИП 0']),
                ],
                'label' => 'ИП',
                'attr'=>['min'=>0,'max'=>40]

            ])
            ->add('furniture',IntegerType::class,[
                'constraints'=>[

                    new Length(['max'=>1, 'maxMessage'=>'Максимальное количество символов 1']),
                    new Range(['max'=>9, 'maxMessage'=>'Максимальная мебель 9']),
                    new Range(['min'=>0, 'minMessage'=>'Минимально мебели 0'])],
                'label'=> 'Мебель',
                'attr'=>['min'=>0,'max'=>9]
            ])
            ->add('engraving', IntegerType::class,[
                'constraints'=>[
                    new Range(['max'=>90, 'maxMessage'=>'Максимальная гравировка 90']),
                    new Range(['min'=>0, 'minMessage'=>'Минимальная гравировка 0'])],
                'label'=> 'Гравировка',
                'attr'=>['min'=>0,'max'=>90]
            ])
            ->add('ipRecommended', IntegerType::class,[
                'property_path'=>'hid.ipRecommended',
                'constraints'=>[
                    new Range(['max'=>40, 'maxMessage'=>'Максимальный ИП 40']),
                    new Range(['min'=>0, 'minMessage'=>'Минимальный ИП 0'])
                ],
                'label' => 'Рекомендованный ИП',
                'attr'=>['min'=>0,'max'=>40]
            ])
            ->add('furnitureRecommended', IntegerType::class, [
                'property_path'=>'hid.furnitureRecommended',
                'constraints'=>[
                    new Length(['max'=>1, 'maxMessage'=>'Максимальное количество символов 1']),
                    new Range(['max'=>9, 'maxMessage'=>'Максимальная мебель 9']),
                    new Range(['min'=>0, 'minMessage'=>'Минимально мебели 0'],
                    )],
                'label'=> 'Рекомендованная Мебель',
                'attr'=>['min'=>0,'max'=>9]
            ])
            ->add('engravingRecommended', IntegerType::class,[
                'property_path'=>'hid.engravingRecommended',
                'constraints'=>[
                    new Range(['max'=>90, 'maxMessage'=>'Максимальная гравировки 90']),
                    new Range(['min'=>0, 'minMessage'=>'Минимальная гравировка 0'])],
                'label'=> 'Рекомендованная Гравировка',
                'attr'=>['min'=>0,'max'=>90]
            ])
            ->add('general', ChoiceType::class,[
                'property_path'=>'hid.general',
                'label'=>'Общий рейтинг',
                'choices'=>[
                    'S+'=>'S+',
                    'S'=>'S',
                    'A'=>'A',
                    'B'=>'B',
                    'C'=>'C',
                    'D'=>'D',

                ]
            ])
            ->add('pve', ChoiceType::class,[
                'property_path'=>'hid.pve',
                'label'=>'ПвЕ рейтинг',
                'choices'=>[
                    'S+'=>'S+',
                    'S'=>'S',
                    'A'=>'A',
                    'B'=>'B',
                    'C'=>'C',
                    'D'=>'D',

                ]
            ])
            ->add('pvp', ChoiceType::class,[
                'property_path'=>'hid.pvp',
                'label'=>'ПвП рейтинг',
                'choices'=>[
                    'S+'=>'S+',
                    'S'=>'S',
                    'A'=>'A',
                    'B'=>'B',
                    'C'=>'C',
                    'D'=>'D',

                ]
            ])
            ->add('distortedWorld', ChoiceType::class,[
                'property_path'=>'hid.distortedWorld',
                'label'=>'Искаженный мир рейтинг',
                'choices'=>[
                    'S+'=>'S+',
                    'S'=>'S',
                    'A'=>'A',
                    'B'=>'B',
                    'C'=>'C',
                    'D'=>'D',

                ]
            ])
            ->add('events', ChoiceType::class,[
                'property_path'=>'hid.events',
                'label'=>'Ивент рейтинг',
                'choices'=>[
                    'S+'=>'S+',
                    'S'=>'S',
                    'A'=>'A',
                    'B'=>'B',
                    'C'=>'C',
                    'D'=>'D',

                ]
            ])
            ->add('abyss',ChoiceType::class,[
                'property_path'=>'hid.abyss',
                'label'=>'Бездны рейтинг',
                'choices'=>[
                    'S+'=>'S+',
                    'S'=>'S',
                    'A'=>'A',
                    'B'=>'B',
                    'C'=>'C',
                    'D'=>'D',

                ]
            ])
            ->add('imageFile', FileType::class,[
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new File(
                        ['mimeTypes'=>[
                            'image/png',
                        ]])
                ],
                'label'=>'Изображение персонажа'
            ])
            ->add('delete', SubmitType::class,[

                'label'=>'Удалить Героя',

            ])
            ->add('save', SubmitType::class,[
                'label'=>'Сохранить'
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Specifications::class
        ]);
    }

}
