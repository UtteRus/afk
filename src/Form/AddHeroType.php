<?php

namespace App\Form;

use App\Entity\Hero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\File;

class AddHeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('heroName', TextType::class,[
                'label' => 'Имя героя',
                'label_attr'=>['class'=>'CUSTOM_LABEL_CLASS']
            ])
            ->add('fraction', TextType::class,[
                'constraints'=>[
                    new NotBlank(['message'=>'Поле фракции должно быть заполненым'])
                ],
                'label'=>'Фракция'
            ])
            ->add('ipRecommended', IntegerType::class,[
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>2, 'maxMessage'=>'Максимальное количество символов 2']),
                    new Range(['max'=>40, 'maxMessage'=>'Максимальный ИП 40'])
                ],
                'label' => 'Рекомендованный ИП',
                'attr'=>['min'=>0,'max'=>40]
            ])
            ->add('furnitureRecommended', IntegerType::class, [
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>1, 'maxMessage'=>'Максимальное количество символов 1']),
                    new Range(['max'=>9, 'maxMessage'=>'Максимальная мебель 9'])],
                'label'=> 'Рекомендованная Мебель',
                'attr'=>['min'=>0,'max'=>9]
            ])
            ->add('engravingRecommended', IntegerType::class,[
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>2, 'maxMessage'=>'Максимальное количество символов 2']),
                    new Range(['max'=>90, 'maxMessage'=>'Максимальная гравировка 90'])],
                'label'=> 'Рекомендованная Гравировка',
                'attr'=>['min'=>0,'max'=>90]
            ])
            ->add('general', ChoiceType::class,
                ['label'=>'Общий рейтинг',
                    'choices'=>[
                        'S+'=>'S+',
                        'S'=>'S',
                        'A'=>'A',
                        'B'=>'B',
                        'C'=>'C',
                        'D'=>'D',]])
            ->add('pve', ChoiceType::class,
                ['label'=>'ПвЕ рейтинг',
                    'choices'=>[
                        'S+'=>'S+',
                        'S'=>'S',
                        'A'=>'A',
                        'B'=>'B',
                        'C'=>'C',
                        'D'=>'D',]])
            ->add('pvp', ChoiceType::class,
                ['label'=>'ПвП рейтинг',
                    'choices'=>[
                        'S+'=>'S+',
                        'S'=>'S',
                        'A'=>'A',
                        'B'=>'B',
                        'C'=>'C',
                        'D'=>'D',]])
            ->add('distortedWorld', ChoiceType::class,
                ['label'=>'Искаженный мир рейтинг',
                    'choices'=>[
                        'S+'=>'S+',
                        'S'=>'S',
                        'A'=>'A',
                        'B'=>'B',
                        'C'=>'C',
                        'D'=>'D',]])
            ->add('events', ChoiceType::class,
                ['label'=>'Ивент рейтинг',
                    'choices'=>[
                        'S+'=>'S+',
                        'S'=>'S',
                        'A'=>'A',
                        'B'=>'B',
                        'C'=>'C',
                        'D'=>'D',]])
            ->add('abyss',ChoiceType::class,
                ['label'=>'Бездны рейтинг',
                    'choices'=>[
                        'S+'=>'S+',
                        'S'=>'S',
                        'A'=>'A',
                        'B'=>'B',
                        'C'=>'C',
                        'D'=>'D',]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hero::class,
        ]);
    }
}
