<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'api_profile', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function profile(): JsonResponse
    {
        $user = $this->getUser();
        // $user will be your User entity (implements UserInterface)
        return $this->json([
            'id'       => $user->getId(),
            'username' => $user->getUserIdentifier(), // usually the email or username field
            'email'    => method_exists($user, 'getEmail')
                          ? $user->getEmail()
                          : $user->getUserIdentifier(),
            'roles'    => $user->getRoles(),
            // add any other fields you need here…
        ]);
    }
}
