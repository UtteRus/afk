<?php

namespace App\Form;


use App\Entity\Specifications;
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
                    new NotBlank(),
                    new Length(['max'=>2, 'maxMessage'=>'Максимальное количество символов 2']),
                    new Range(['max'=>40, 'maxMessage'=>'Максимальный ИП 40'])
                ],
                'label' => 'ИП'
            ])
            ->add('furniture',IntegerType::class,[
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>1, 'maxMessage'=>'Максимальное количество символов 1']),
                    new Range(['max'=>9, 'maxMessage'=>'Максимальная мебель 9'])],
                'label'=> 'Мебель'
            ])
            ->add('engraving', IntegerType::class,[
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>2, 'maxMessage'=>'Максимальное количество символов 2']),
                    new Range(['max'=>90, 'maxMessage'=>'Максимальная мебель 90'])],
                'label'=> 'Гравировка'
            ])
            ->add('ipRecommended', IntegerType::class,[
                'property_path'=>'hid.ipRecommended',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>2, 'maxMessage'=>'Максимальное количество символов 2']),
                    new Range(['max'=>40, 'maxMessage'=>'Максимальный ИП 40'])
                ],
                'label' => 'Рекомендованный ИП'
            ])
            ->add('furnitureRecommended', IntegerType::class, [
                'property_path'=>'hid.furnitureRecommended',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>1, 'maxMessage'=>'Максимальное количество символов 1']),
                    new Range(['max'=>9, 'maxMessage'=>'Максимальная мебель 9'])],
                'label'=> 'Рекомендованная Мебель'
            ])
            ->add('engravingRecommended', IntegerType::class,[
                'property_path'=>'hid.engravingRecommended',
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>2, 'maxMessage'=>'Максимальное количество символов 2']),
                    new Range(['max'=>90, 'maxMessage'=>'Максимальная мебель 90'])],
                'label'=> 'Рекомендованная Гравировка'
            ])
            ->add('general', TextType::class,[
                'property_path'=>'hid.general',
                'label'=>'Общий рейтинг'
            ])
            ->add('pve', TextType::class,[
                'property_path'=>'hid.pve',
                'label'=>'ПвЕ рейтинг'
            ])
            ->add('pvp', TextType::class,[
                'property_path'=>'hid.pvp',
                'label'=>'ПвП рейтинг'
            ])
            ->add('distortedWorld', TextType::class,[
                'property_path'=>'hid.distortedWorld',
                'label'=>'Искаженный мир рейтинг'
            ])
            ->add('events', TextType::class,[
                'property_path'=>'hid.events',
                'label'=>'Ивент рейтинг'
            ])
            ->add('abyss',TextType::class,[
                'property_path'=>'hid.abyss',
                'label'=>'Бездны рейтинг'
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
