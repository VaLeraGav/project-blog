<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user, ParameterBagInterface $parameterBag): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $privateKey = $parameterBag->get('api.jwt.privateKey');

        $payload = [
            'userId' => $user->getId(),
            'iat' => time(),
        ];
        $jwt = JWT::encode($payload, $privateKey, 'RS256');

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'jwt' =>  $jwt,
        ]);
    }

    /*
     * чтобы работал этот нужно выключиь в security firebase: custom_authenticators
     */
    #[Route('/old_api/login', name: 'api_login_old', methods: ['POST'])]
    public function indexOld(#[CurrentUser] ?User $user): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiLoginController.php',
        ]);
    }
}
