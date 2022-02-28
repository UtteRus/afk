<?php

namespace App\Form;

use App\Entity\Hero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('Velue', TextType::class,[
                'constraints'=>[
                    new NotBlank(['message'=>'Не должно быть пустым']),
                    ],
                'label'=>'Ценность героя'
                ])
            ->add('ipRecommended', TextType::class,[
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>2, 'maxMessage'=>'Максимальное количество символов 2']),
                    new Range(['max'=>40, 'maxMessage'=>'Максимальный ИП 40'])
                ],
                'label' => 'Рекомендованный ИП'
            ])
            ->add('furnitureRecommended', TextType::class, [
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>1, 'maxMessage'=>'Максимальное количество символов 1']),
                    new Range(['max'=>9, 'maxMessage'=>'Максимальная мебель 9'])],
                'label'=> 'Рекомендованная Мебель'
            ])
            ->add('engravingRecommended', TextType::class,[
                'constraints'=>[
                    new NotBlank(),
                    new Length(['max'=>2, 'maxMessage'=>'Максимальное количество символов 2']),
                    new Range(['max'=>90, 'maxMessage'=>'Максимальная гравировка 90'])],
                'label'=> 'Рекомендованная Гравировка'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hero::class,
        ]);
    }
}
