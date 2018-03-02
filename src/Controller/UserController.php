<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function loginFormAction(AuthenticationUtils $authUtils)
    {
        return $this->render('front/login.html.twig', [
            'error' => $authUtils->getLastAuthenticationError(),
            'last_username' => $authUtils->getLastUsername()
        ]);
    }
    
    /**
     * @Route("/login/redirect", name="login_redirect")
     * @IsGranted("ROLE_USER")
     */
    public function loginRedirect()
    {
        if ($this->getUser() instanceof Member) {
            return $this->redirectToRoute("member_dashboard");
        }
        return $this->redirectToRoute("member_dashboard");
    }
}