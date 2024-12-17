<?php
namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SecurityController extends AbstractController
{
    private $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    
    #[Route('/login', name: 'app_login')]    
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // generate CSRF tokn

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/register', name: 'page_register')] 
    public function register(): Response
    {
        return $this->render('auth/register.html.twig');
    }

    #[Route(path: '/forgot', name: 'auth_forgot')]
    public function forgot(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            // Generate a reset token (this is just an example, you should implement a proper token generation and storage)
            $resetToken = bin2hex(random_bytes(32));
            // Send the email
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


    
     #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}