<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\User\ProductOwner;

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
     * @Security("has_role('ROLE_USER')")
     */
    public function loginRedirect()
    {
        if ($this->getUser() instanceof ProductOwner) {
            return $this->redirectToRoute("project_workspace", [
                'slug' => $this->getUser()->getProjects()->first()->getSlug()
            ]);
        }
        return $this->redirectToRoute("member_dashboard");
    }
    
    /**
     * @Route("/profile", name="my_profile", methods={"GET"})
     * @Security("has_role('ROLE_USER')")
     */
    public function getMyProfile()
    {
        return $this->render('members/profile.html.twig');
    }
}