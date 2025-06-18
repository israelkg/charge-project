<?php

namespace App\Document;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTimeImmutable;
use MongoDB\BSON\ObjectId;

class User implements UserInterface, PasswordAuthenticatedUserInterface{
    private ?ObjectId $id = null;
    private ?string $email = null;
    private ?string $password = null;
    private array $roles = [];

    private ?DateTimeImmutable $dataCriacao = null;
    private ?DateTimeImmutable $dataAtualizacao = null;


    public function __construct(){
        $this->dataCriacao = new DateTimeImmutable();
        $this->dataAtualizacao = new DateTimeImmutable();
    }

    public function getId(): ?string{
        return $this->id ? (string) $this->id : null;
    }

    
    public function getUserIdentifier(): string{
        return (string) $this->email;
    }

    public function getRoles(): array{
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self{
        $this->roles = $roles;
        $this->dataAtualizacao = new DateTimeImmutable();
        return $this;
    }

    public function getPassword(): ?string{
        return $this->password;
    }
    public function setPassword(string $password): self{
        $this->password = $password;
        $this->dataAtualizacao = new DateTimeImmutable();
        return $this;
    }

    public function getEmail(): ?string{
        return $this->email;
    }
    public function setEmail(string $email): self{
        $this->email = $email;
        $this->dataAtualizacao = new DateTimeImmutable();
        return $this;
    }

    public function getSalt(): ?string{
        return null;
    }

    public function eraseCredentials(): void{
        // $this->password = null; // Se você quisesse apagar a senha após o uso, mas não é comum
    }



    public function getDataCriacao(): ?DateTimeImmutable{
        return $this->dataCriacao;
    }

    public function getDataAtualizacao(): ?DateTimeImmutable{
        return $this->dataAtualizacao;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'roles' => $this->roles,
            'data_criacao' => $this->dataCriacao,
            'data_atualizacao' => $this->dataAtualizacao,
        ];
    }
}
