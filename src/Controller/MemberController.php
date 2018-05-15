<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER")
     */
    public function dashboardAction(CommunityMemberManager $communityMemberManager)
    {
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
    public function createMember(Request $request)
    {
        $member = $this->get(UserManager::class)->createUser(
            $request->request->all(),
            Member::TYPE_MEMBER
        );
        
        $this->get(AuthenticationManager::class)->authenticate($request, $member);
        
        return new JsonResponse($member, 201);
    }
}