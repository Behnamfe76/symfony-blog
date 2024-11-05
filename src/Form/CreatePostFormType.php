<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\PostCategory;
use App\Entity\PostType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreatePostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attachment', TextType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'فایلی برای آپلود انتخاب نشده.',
                    ]),
                ]
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('content')
            ->add('postType', EntityType::class, [
                'class' => PostType::class,
                'choice_label' => 'name',
            ])
            ->add('postCategory', EntityType::class, [
                'class' => PostCategory::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,

        ]);
    }
}
