<?php

namespace App\Controller;

use App\Repository\ProgramsRepository;
use App\Service\ImportSubjectsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ImportController extends AbstractController
{
    #[Route('/import-subjects')]
    public function importSubjects(
        Request $request,
        ProgramsRepository $programsRepository,
        ImportSubjectsService $importSubjects,
    ): JsonResponse {
        $currentProgramId = $request->getPayload()->getString('currentProgram');
        $programToImportId = $request->getPayload()->getString('programToImport');
        $currentProg = $programsRepository->find($currentProgramId);
        if (null === $currentProg) {
            return new JsonResponse(['Brak programu'], Response::HTTP_CONFLICT);
        }
        $programToImport = $programsRepository->find($programToImportId);
        if (null === $programToImport) {
            return new JsonResponse(['Brak programu do importu przedmiotów'], Response::HTTP_CONFLICT);
        }

        $int = $importSubjects->copySubjectsToProgram($currentProg, $programToImport);

        return new JsonResponse(['copied' => $int]);
    }
}
