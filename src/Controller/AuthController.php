<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods:['GET','POST'])]
    public function login(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->isMethod('POST')) {
            $email = (string) $request->request->get('_username', '');
            $password = (string) $request->request->get('_password', '');
            
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user && $hasher->isPasswordValid($user, $password)) {
                // simple session-based login: store user id in session
                $session = $request->getSession();
                if (!$session->isStarted()) { $session->start(); }
                $session->set('user_id', $user->getId());
                
                $target = $request->request->get('_target_path') ?: '/board';
                return new RedirectResponse($target);
            }
            // wrong credentials
            $this->addFlash('error', 'Invalid credentials');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $request->request->get('_username', ''),
            'error' => null,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(\Symfony\Component\HttpFoundation\RequestStack $rs): void
    {
        $session = $rs->getSession();
        if ($session) { $session->clear(); }
        header('Location: /login');
        exit;
    }

    #[\Symfony\Component\Routing\Annotation\Route('/login-success', name: 'app_login_success', methods:['GET'])]
    public function loginSuccess(Request $request): Response
    {
        $session = $request->getSession();
        $sessionId = $session ? $session->getId() : null;
        $userId = $session ? $session->get('user_id') : null;
        @file_put_contents(
            __DIR__ . '/../../var/log/auth_debug.log',
            sprintf("%s LOGIN_SUCCESS_PAGE session_id=%s user_id=%s session_started=%s\n", (new \DateTime())->format(DATE_ATOM), $sessionId ?: 'none', $userId ?: 'none', $session && $session->isStarted() ? 'yes' : 'no'),
            FILE_APPEND
        );
        return $this->render('security/login_success.html.twig', [
            'session_id' => $sessionId,
            'user_id' => $userId,
        ]);
    }
}
