<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Manager\Community\{
    CommunityManager,
    MemberManager,
    NewsManager,
    ProjectManager
};

class CommunityController extends Controller
{
    /**
     * @Route("/communities/new", name="new_community", methods={"GET"})
     * @IsGranted("ROLE_LEAD")
     */
    public function newAction()
    {
        return $this->render('communities/new.html.twig');
    }
    
    /**
     * @Route("/communities", name="create_community", methods={"POST"})
     * @IsGranted("ROLE_LEAD")
     */
    public function createAction(Request $request, CommunityManager $communityManager)
    {
        return new JsonResponse($communityManager->createCommunity(
            $request->request->get('name'),
            $request->files->get('picture')
        ), 201);
    }
    
    /**
     * @Route("/communities", name="communities_list", methods={"GET"})
     */
    public function getAllAction(CommunityManager $communityManager)
    {
        return $this->render('communities/list.html.twig', [
            'communities' => $communityManager->getAll()
        ]);
    }

    /**
     * @Route("/communities/{slug}", name="community_details", methods={"GET"})
     */
    public function getAction(string $slug, CommunityManager $communityManager, MemberManager $memberManager, NewsManager $newsManager, ProjectManager $projectManager)
    {
        $community = $communityManager->get($slug);
        return $this->render('communities/details.html.twig', [
            'community' => $community,
            'members' => $memberManager->getCommunityMembers($community),
            'news' => $newsManager->getCommunityNews($community),
            'projects' => $projectManager->getCommunityProjects($community),
        ]);
    }
}