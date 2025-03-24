<?php

declare(strict_types=1);

namespace App\Controller;

use ApiPlatform\Metadata\IriConverterInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: 'POST')]
    public function login(IriConverterInterface $iriConverter, #[CurrentUser] User $user = null): Response
    {
        if (!$user) {
            return $this->json([
                'error' => 'Check to make sure Content-Type is set to application/json'
            ], 401);
        }
        return new Response(null, 204, [
            'Location' => $iriConverter->getIriFromResource($user)
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route('/logout', name: 'app_logout', methods: 'POST')]
    public function logout(): void {
        throw new \Exception('This should never be reached.');
    }
}
