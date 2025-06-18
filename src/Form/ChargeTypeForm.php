<?php

namespace App\Form;

use App\Model\ChargeData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; 
use Symfony\Component\Form\Extension\Core\Type\DateType;   
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;  
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;   
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ChargeTypeForm extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder
            ->add('currentStep', HiddenType::class, [
                'mapped' => false,
                'data' => '2',
            ])
            // Dados da Cobrança
            ->add('value', MoneyType::class, [
                'label' => 'Valor da Cobrança (R$)',
                'currency' => false, 
                'divisor' => 100,
                'html5' => false,
                'attr' => [
                    'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descrição da Cobrança',
                'attr' => ['rows' => 5, 'placeholder' => 'Detalhes sobre o que está sendo cobrado', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'row_attr' => ['class' => 'mb-4'],
                'required' => true,
            ])
            
            ->add('paymentOption', ChoiceType::class, [
                'label' => 'Como o seu cliente poderá pagar?',
                'choices' => [
                    'À vista ou parcelado' => 'A_VISTA_PARCELADO',
                    'Assinatura' => 'ASSINATURA',
                ],
                'expanded' => true, 
                'multiple' => false,
                'attr' => ['class' => 'flex space-x-4 mb-4', 'id' => 'payment_option_group'],
                'label_attr' => ['class' => 'block text-lg font-bold text-gray-800 mb-2'],
                'row_attr' => ['class' => 'mb-6'],
                'data' => 'A_VISTA_PARCELADO'
            ])
            ->add('chargeType', ChoiceType::class, [
                'label' => 'Tipo de Cobrança',
                'choices' => [
                    'Boleto' => 'BOLETO',
                    'Pix' => 'PIX',        
                    'Cartão de Crédito' => 'CREDIT_CARD', 
                ],
                'placeholder' => 'Selecione o tipo', 
                'attr' => ['class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'row_attr' => ['class' => 'mb-4'],
            ])

            //AVISTA/PARCELAMENTO
            ->add('installments', ChoiceType::class, [
                'label' => 'Parcelamento',
                'choices' => [
                    'À vista (R$ 0,00)' => 1,
                ],
                'placeholder' => false,
                'required' => false, 
                'attr' => ['class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'row_attr' => ['class' => 'mb-4', 'id' => 'installments_row'], 
            ])
            ->add('dueDate', DateType::class, [
                'label' => 'Data de Vencimento',
                'widget' => 'single_text', 
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true,
                'attr' => ['class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm datepicker-input'],
                'row_attr' => ['class' => 'mb-4'],
            ])

            //ASSINATURA
            ->add('subscriptionFrequency', ChoiceType::class, [
                'label' => 'Frequência da Assinatura',
                'choices' => [
                    'Mensal' => 'MONTHLY',
                    'Anual' => 'YEARLY',
                    'Trimestral' => 'QUARTERLY',
                ],
                'data' => 'Mensal',
                'required' => true, // A obrigatoriedade será condicional via JS
                'attr' => ['class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'], 
                'row_attr' => ['class' => 'mb-4'], 
            ])
            ->add('dueDateAss', DateType::class, [
                'label' => 'Vencimento da 1ª cobrança',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => true,// A obrigatoriedade será condicional via JS
                'attr' => ['class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm datepicker-input'],
                'row_attr' => ['class' => 'mb-4'], 
            ])


            // Dados do Cliente
            ->add('clientCpfCnpj', TextType::class, [
                'label' => 'CPF/CNPJ do Cliente',
                'attr' => ['placeholder' => 'Digite o CPF ou CNPJ', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'required' => false, 
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('clientName', TextType::class, [
                'label' => 'Nome do Cliente',
                'attr' => ['placeholder' => 'Digite o nome cliente', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'row_attr' => ['class' => 'mb-4'],
                'required' => false,
            ])
            ->add('clientEmail', TextType::class, [
                'label' => 'Email do Cliente',
                'attr' => ['placeholder' => 'Digite um e-mail para contato', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'row_attr' => ['class' => 'mb-4'],
                'required' => false,
            ])
            ->add('clientPhone', TextType::class, [
                'label' => 'Celular do Cliente',
                'attr' => ['placeholder' => '(00) 00000-0000', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'required' => false, 
                'row_attr' => ['class' => 'mb-4'],
            ])
            ->add('clientAddress', TextareaType::class, [
                'label' => 'Endereço do Cliente',
                'attr' => ['rows' => 3, 'placeholder' => 'Rua, número, bairro, cidade, estado (opcional)', 'class' => 'mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm'],
                'required' => true, 
                'row_attr' => ['class' => 'mb-4'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void{
        $resolver->setDefaults([
            'data_class' => ChargeData::class, 
            'validation_groups' => function(FormInterface $form){
                $step = $form->get('currentStep')->getData();
                return match($step){
                    '1' => ['Default', 'step1'],
                    '2' => ['Default', 'step2'],
                };
            },
            'attr' => ['novalidate' => 'novalidate'], 
        ]);
    }
}