<?php

namespace App\Form;

use App\Document\User; 
use App\Validator\Constraints\UniqueEmailValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use App\Validator\Constraints\UniqueEmail;

class RegistrationFormType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Endereço de E-mail',
                'attr' => ['placeholder' => 'seu.email@exemplo.com', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor, insira um endereço de e-mail.',
                    ]),
                    new Email([
                        'message' => 'Por favor, insira um e-mail válido.',
                    ]),
                    new UniqueEmail(),
                ],
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Senha',
                    'attr' => ['placeholder' => '********', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Por favor, insira uma senha.',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Sua senha deve ter pelo menos {{ limit }} caracteres.',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmar Senha',
                    'attr' => ['placeholder' => '********', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                ],
                'invalid_message' => 'As senhas não correspondem.',
                
                'mapped' => false, 
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Concordo com os termos',
                'mapped' => false, 
                'constraints' => [
                    new IsTrue([
                        'message' => 'Você deve concordar com os termos para continuar.',
                    ]),
                ],
                'attr' => ['class' => 'mr-2'],
                'label_attr' => ['class' => 'text-sm text-gray-700'], 
                'row_attr' => ['class' => 'mb-4 flex items-center'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => User::class, 
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}