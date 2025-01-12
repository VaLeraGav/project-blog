<?php

declare(strict_types=1);

namespace App\Requests;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints\UniqueEmail;

class UserRequest
{

    // Eсли используешь Form то здесь не нужно использовать Validator

    #[Assert\NotBlank(message: "Email не должен быть пустым. 0")]
    // #[UniqueEmail()]
    public ?string $email;
    #[Assert\NotBlank(message: "Пароль не должен быть пустым.")]
    #[Assert\Length(min: 6, minMessage: "Пароль должен содержать минимум {{ limit }} символов.")]
    public ?string $password;

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }


//    #[Assert\Callback()]
//    public function validate(ExecutionContextInterface $context): void
//    {
//        if ($this->email) {
//            // Здесь можно добавить свою логику проверки email
//            if (true) { // Замените на вашу логику проверки
//                $context
//                    ->buildViolation('Email некорректен')
//                    ->atPath('email')
//                    ->addViolation();
//            }
//        }
//    }
}