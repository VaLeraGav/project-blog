<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Requests\UserRequest;
use App\Validator\Constraints\UniqueEmail;

class UserValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'required' => true,
                'constraints' => [
                    new UniqueEmail(), // Ваше собственное ограничение
                    new Assert\NotBlank([
                        'message' => 'Please enter a email',
                    ]),
                    new Assert\Callback([$this, 'validateEventDates']),
                ]])
            ->add('password', PasswordType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function validateEventDates($value, ExecutionContextInterface $context): void
    {
//        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $value]);

        $user = true;

        if ($user) {
            $context->buildViolation('Этот адрес электронной почты уже используется. 2')
                ->addViolation();
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserRequest::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true, // удалить "user_validation": [ "This form should not contain extra fields." ],
        ]);
    }
}
