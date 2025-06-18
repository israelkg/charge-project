<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;
use DateTimeImmutable; 


class ChargeData{
    #[Assert\NotBlank(message: 'O nome do cliente é obrigatório.')]
    #[Assert\Length(min: 3, minMessage: 'O nome do cliente deve ter pelo menos {{ limit }} caracteres.')]
    public ?string $clientName = null;
    public ?string $clientCpfCnpj = null;

    #[Assert\NotBlank(message: 'O email do cliente é obrigatório.')]
    #[Assert\Email(message: 'Insira um email válido.')]
    public ?string $clientEmail = null;
    public ?string $clientAddress = null;

    #[Assert\NotBlank(message: 'O valor é obrigatório.')]
    #[Assert\Positive(message: 'O valor deve ser um número positivo.')]
    public ?float $value = null;

    #[Assert\Length(max: 255, maxMessage: 'A descrição pode ter no máximo {{ limit }} caracteres.')]
    public ?string $description = " ";

    #[Assert\NotBlank(message: 'A data de vencimento é obrigatória.')]
    public $dueDate = null;

    #[Assert\NotBlank(message: 'O tipo de cobrança é obrigatório.')]
    #[Assert\Choice(choices: ['BOLETO', 'PIX', 'CREDIT_CARD'], message: 'Escolha um tipo de cobrança válido.')]
    public ?string $chargeType = null;

    #[Assert\NotBlank(message: 'Selecione uma opção de pagamento')]
    #[Assert\Choice(choices: ['A_VISTA_PARCELADO', 'ASSINATURA'], message: 'Opção de pagamento inválida.')]
    public ?string $paymentOption = null;

    #[Assert\Type(type: 'integer', message: 'O número de parcelas deve ser um número inteiro.')]
    #[Assert\GreaterThanOrEqual(value: 1, message: 'O número de parcelas deve ser pelo menos 1.')]
    #[Assert\LessThanOrEqual(value: 12, message: 'O número máximo de parcelas é 12.')]
    public ?int $installments = null;


    #[Assert\Choice(choices: ['MONTHLY', 'YEARLY', 'QUARTERLY'], message: 'Frequência de assinatura inválida.')]
    public ?string $subscriptionFrequency = null; 

    #[Assert\Type(\DateTimeImmutable::class, message: 'Data de início da assinatura inválida.')]
    #[Assert\GreaterThanOrEqual(value: 'today', message: 'A data de início da assinatura não pode ser no passado.')]
    public $dueDateAss = null;
}