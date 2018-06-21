<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\User\ProductOwner;

use App\Manager\User\NotificationManager;

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
    
    /**
     * @Route("/users/me/notifications/read", name="read_notifications", methods={"PATCH"})
     * @Security("has_role('ROLE_USER')")
     */
    public function readNotifications(Request $request, NotificationManager $notificationManager)
    {
        $notificationManager->read($request->request->get('ids'));
        return new Response(null, 204);
    }
}