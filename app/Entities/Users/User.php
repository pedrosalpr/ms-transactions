<?php

declare(strict_types=1);

namespace App\Entities\Users;

class User
{

    private $id;
    private $name;
    private $email;
    private $cpfCnpj;
    private $userType;

    public static function fromArray(array $data): self
    {
        $user = new self();

        $user->id = $data['id'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->cpfCnpj = $data['cpf_cnpj'] ?? null;
        $user->userType = $data['user_type'] ?? null;
        $user->name = $data['name'] ?? '';

        return $user;
    }

    public function getUserType()
    {
        return $this->userType;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getCpfCnpj()
    {
        return $this->cpfCnpj;
    }
}
