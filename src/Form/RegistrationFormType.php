<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'نام کاربری الزامی است.'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 15,
                        'minMessage' => ' نام کاربری باید حداقل شامل {{ limit }} کاراکتر باشد',
                        'maxMessage' => ' نام کاربری باید حداکثر شامل {{ limit }} کاراکتر باشد',
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'mapped' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'ایمیل الزامی است'
                    ]),
                    new Email([
                        'message' => 'ایمیل معتبر نمیباشد است'
                    ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'باید با شرایط و قوانین موافقت کنید.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'off'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'رمز عبور را وارد کنید',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => ' رمز عبور باید حداقل شامل {{ limit }} کاراکتر باشد',
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'رمز عبور',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'تکرار رمز عبور',
                ],
                'invalid_message' => 'رمز عبور با تکرار مطابقت ندارد',
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'fields' => ['username'],
                    'message' => 'نام کاربری قبلا انتخاب شده است.',
                ]),
            ],
        ]);
    }
}
