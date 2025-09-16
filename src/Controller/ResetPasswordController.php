<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $param,
    ) {
    }

    #[Route('', name: 'app_forgot_password_request', methods: 'POST')]
    public function request(Request $request, MailerInterface $mailer): JsonResponse
    {
        $email = $request->getPayload()->getString('email');

        if (empty($email)) {
            return new JsonResponse([
                'status' => 'fail',
                'error' => 'Email is required',
            ], Response::HTTP_BAD_REQUEST);
        }

        if ($this->processSendingPasswordResetEmail($email, $mailer)) {
            return new JsonResponse(['status' => 'sent'], Response::HTTP_OK);
        }

        return new JsonResponse(['status' => 'fail'], Response::HTTP_BAD_REQUEST);
    }

    /* #[Route('/check-email', name: 'app_check_email')] */
    /* public function checkEmail(): Response */
    /* { */
    /*     // Generate a fake token if the user does not exist or someone hit this page directly. */
    /*     // This prevents exposing whether or not a user was found with the given email address or not */
    /*     if (null === ($resetToken = $this->getTokenObjectFromSession())) { */
    /*         $resetToken = $this->resetPasswordHelper->generateFakeResetToken(); */
    /*     } */

    /*     return $this->render('reset_password/check_email.html.twig', [ */
    /*         'resetToken' => $resetToken, */
    /*     ]); */
    /* } */

    #[Route('/reset/{token}', name: 'app_reset_password', methods: 'POST')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, ?string $token = null): JsonResponse
    {
        if (null === $token) {
            return new JsonResponse([
                'status' => 'fail',
                'error' => 'Token missing',
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            /** @var User $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            return new JsonResponse([
                'status' => 'fail',
                'error' => 'Error validating token',
            ], Response::HTTP_CONFLICT);
        }

        $this->resetPasswordHelper->removeResetRequest($token);
        $plainPassword = $request->getPayload()->getString('plainPassword');
        $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
        $this->entityManager->flush();
        $this->cleanSessionAfterReset();

        return new JsonResponse([
            'status' => 'success',
        ], Response::HTTP_OK);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer): bool
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);
        if (!$user) {
            return true;
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            return true;
        }

        $email = (new TemplatedEmail())
            ->from(new Address('pensum@app.jslomian.dev', 'Pensum UPSL'))
            ->to((string) $user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'fe_url' => $this->param->get('fe_url'),
            ]);

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return true;
    }
}
