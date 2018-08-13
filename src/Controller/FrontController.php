<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;

use App\Manager\Community\CommunityManager;
use App\Manager\MemberManager;
use App\Manager\Project\ProjectManager;

class FrontController extends Controller
{
    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function homepageAction(CommunityManager $communityManager, MemberManager $memberManager, ProjectManager $projectManager)
    {
        return $this->render('front/homepage.html.twig', [
            'nb_camps' => $communityManager->countAll(),
            'nb_members' => $memberManager->countAll(),
            'nb_projects' => $projectManager->countAll(),
        ]);
    }
}
