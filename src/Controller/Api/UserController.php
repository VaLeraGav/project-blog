<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserValidationType;
use App\Requests\UserRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Collection;

class UserController extends AbstractController
{
    #[Route('/api/1/register_validation', name: 'api_1_blog', methods: ['POST'], format: 'json')]
    public function register(Request $request): Response
    {
        $user = new UserRequest();

        // Form ипользовать для Api плохо, так как будут проблемы с типами данных https://github.com/symfony/symfony/discussions/46789
        $form = $this->createForm(UserValidationType::class, $user);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->json(['status' => 'User registered successfully!'], Response::HTTP_OK);
        }

        return $this->json(['errors' => $this->formatFormErrors($form)], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/api/2/register_validation', name: 'api_2_blog', methods: ['POST'], format: 'json')]
    public function register2(Request $request, ValidatorInterface $validator): Response
    {
        $data = json_decode($request->getContent(), true);

        $userRequest = new UserRequest();
        $userRequest->setEmail($data['email'] ?? null)->setPassword($data['password']);

        $errors = $validator->validate($userRequest);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $propertyPath = trim($error->getPropertyPath(),'[]');;
                $errorMessages[$propertyPath][] = $error->getMessage();
            }

            return $this->json([
                'success' => false,
                'errors' => $errorMessages,
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['status' => 'User registered successfully!'], Response::HTTP_OK);
    }

    #[Route('/api/3/register_validation', name: 'api_3_blog', methods: ['POST'], format: 'json')]
    public function register3(Request $request, ValidatorInterface $validator): Response
    {
        $constraints = new Collection([
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 6]),
            ],
        ]);

        $data = json_decode($request->getContent(), true);

        $errors = $validator->validate($data, $constraints);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $propertyPath = trim($error->getPropertyPath(),'[]');;
                $errorMessages[$propertyPath][] = $error->getMessage();
            }

            return $this->json([
                'success' => false,
                'errors' => $errorMessages,
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['status' => 'User registered successfully!'], Response::HTTP_OK);
    }

    // заметил проблему что сообщения про валиюацию приходят с в одной строке и в HttpExceptionInterface
    #[Route('/api/4/register_validation', name: 'api_4_blog', methods: ['POST'], format: 'json')]
    public function register4(#[MapRequestPayload(acceptFormat: 'json')] UserRequest $request): Response
    {
        return $this->json(['status' => 'User registered successfully!'], Response::HTTP_OK);
    }

    private function formatFormErrors($form): array
    {
        $errors = [];

        foreach ($form->getErrors(true) as $error) {
            $field = $error->getOrigin()->getName();
            $errors[$field][] = $error->getMessage();
        }

        return $errors;
    }
}