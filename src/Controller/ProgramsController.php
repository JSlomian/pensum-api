<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProgramsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProgramsController extends AbstractController
{
    #[ApiResource]
    #[Route('/api/programs_list', name: 'app_programs', methods: ['GET'])]
    public function index(
        ProgramsRepository $programsRepository
    ): JsonResponse
    {
        $programs = $programsRepository->findAll();
        return $this->json($programs);
    }
}
