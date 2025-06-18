<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable; 


class ChargeData{
    #[Assert\NotBlank(groups: ['step2'])]
    #[Assert\NotBlank(message: 'O nome do cliente é obrigatório.')]
    #[Assert\Length(min: 3, minMessage: 'O nome do cliente deve ter pelo menos {{ limit }} caracteres.')]
    public ?string $clientName = null;

    #[Assert\NotBlank(groups: ['step2'])]
    #[Assert\NotBlank(['message' => 'O cpf/cnpj do cliente é obrigatório'])]
    #[Assert\Length(['min' => 11, 'max' => 14])]
    public ?string $clientCpfCnpj = null;

    #[Assert\NotBlank(groups: ['step2'])]
    #[Assert\NotBlank(message: 'Número de telefone é obrigatório')]
    #[Assert\Regex([
        'pattern' => '/^\(?\d{2}\)?[\s-]?\d{4,5}-?\d{4}$/',
        'message' => 'Número de telefone inválido.',])]
    public ?string $clientPhone = null;

    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\NotBlank(message: 'Email do cliente é obrigatório.')]
    #[Assert\Email(message: 'Email inválido.')]
    public ?string $clientEmail = null;

    // #[Assert\NotBlank(groups: ['step2'])]
    public ?string $clientAddress = null;

    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\NotBlank(message: 'Valor é obrigatório.')]
    #[Assert\Regex(['pattern' => '/^\d+([,\.]\d{1,2})?$/', 'message' => 'Valor inválido.',])]
    #[Assert\Positive(message: 'Valor inválido.')]
    #[Assert\GreaterThan([
        'value' => 0,
        'message' => 'Valor inváldo.',
    ])]
    public ?float $value = null;

    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\Length(max: 255, maxMessage: 'A descrição pode ter no máximo {{ limit }} caracteres.')]
    public ?string $description = " ";

    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\NotBlank(message: 'A data de vencimento é obrigatória.')]
    public $dueDate = null;

    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\NotBlank(message: 'O tipo de cobrança é obrigatório.')]
    #[Assert\Choice(choices: ['BOLETO', 'PIX', 'CREDIT_CARD'], message: 'Escolha um tipo de cobrança válido.')]
    public ?string $chargeType = null;

    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\NotBlank(message: 'Selecione uma opção de pagamento')]
    #[Assert\Choice(choices: ['A_VISTA_PARCELADO', 'ASSINATURA'], message: 'Opção de pagamento inválida.')]
    public ?string $paymentOption = null;

    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\Type(type: 'integer', message: 'O número de parcelas deve ser um número inteiro.')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'O número de parcelas deve ser pelo menos 1.')]
    #[Assert\LessThanOrEqual(value: 12, message: 'O número máximo de parcelas é 12.')]
    public ?int $installments = null;


    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\Choice(choices: ['MONTHLY', 'YEARLY', 'QUARTERLY'], message: 'Frequência de assinatura inválida.')]
    public ?string $subscriptionFrequency = null; 

    #[Assert\NotBlank(groups: ['step1'])]
    #[Assert\NotBlank(message: 'A data de vencimento é obrigatória.')]
    #[Assert\GreaterThanOrEqual(value: 'today', message: 'A data de início da assinatura não pode ser no passado.')]
    public $dueDateAss = null;
}