<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Manager\CommunityManager;

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
        $community = $communityManager->createCommunity(
            $request->request->get('name'),
            $request->files->get('picture')
        );
        return new JsonResponse($community, 201);
    }
}