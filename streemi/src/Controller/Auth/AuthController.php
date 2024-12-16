<?php

// src/Controller/Auth/AuthController.php
namespace App\Controller\Auth;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    #[Route(path: '/login2', name: 'page_login')]
    public function login(): Response
    {
        return $this->render('auth/login.html.twig');
    }

    #[Route(path: '/register', name: 'page_register')]
    public function register(): Response
    {
        return $this->render('auth/register.html.twig');
    }

    #[Route(path: '/forgot', name: 'page_forgot')]
    public function forgot(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            // Generate a reset token (this is just an example, you should implement a proper token generation and storage)
            $resetToken = bin2hex(random_bytes(32));
            // Send the email
            $this->emailService->sendForgotPasswordEmail($email, $resetToken);
            // Add a flash message or any other response
            $this->addFlash('success', 'An email has been sent to reset your password.');
        }

        return $this->render('auth/forgot.html.twig');
    }

    #[Route(path: '/reset', name: 'page_reset')]
    public function reset(): Response
    {
        return $this->render('auth/reset.html.twig');
    }

    #[Route(path: '/confirm', name: 'page_confirm')]
    public function confirm(): Response
    {
        return $this->render('auth/confirm.html.twig');
    }
}