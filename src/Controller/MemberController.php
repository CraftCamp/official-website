<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Manager\UserManager;
use App\Entity\User\Member;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Security\Authentication\AuthenticationManager;

use App\Manager\Community\MemberManager as CommunityMemberManager;

class MemberController extends Controller
{
    /**
     * @Route("/members/dashboard", name="member_dashboard", methods={"GET"})
     */
    public function dashboardAction(CommunityMemberManager $communityMemberManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('members/dashboard.html.twig', [
            'communities' => $communityMemberManager->getMemberCommunities($this->getUser()),
        ]);
    }
    
    /**
     * @Route("/members/new", name="member_registration", methods={"GET"})
     */
    public function newAction()
    {
        return $this->render('members/new.html.twig');
    }
    
    /**
     * @Route("/members", name="member_creation", methods={"POST"})
     */
    public function createMember(Request $request, UserManager $userManager, AuthenticationManager $authenticationManager)
    {
        $member = $userManager->createUser($request->request->all(), Member::TYPE_MEMBER);
        
        $authenticationManager->authenticate($request, $member);
        
        return new JsonResponse($member, 201);
    }
}