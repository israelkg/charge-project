<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class UniqueEmail extends Constraint
{
    public string $message = 'Este endereço de e-mail já está em uso.';

    public function __construct(mixed $options = null, array $groups = null, mixed $payload = null){
        parent::__construct($options, $groups, $payload);
    }

    public function validatedBy(): string{
        return static::class.'Validator'; 
    }

    public function getTargets(): string|array{
        return self::PROPERTY_CONSTRAINT;
    }
}