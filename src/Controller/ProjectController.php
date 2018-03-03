<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Organization;
use App\Entity\User\ProductOwner;

use App\Manager\OrganizationManager;
use App\Manager\UserManager;

use App\Security\Authentication\AuthenticationManager;

use App\Manager\ProjectManager;

class ProjectController extends Controller {

    /**
     * @Route("/projects", name="projects_list", methods={"GET"})
     */
    public function getListAction()
    {
        return $this->render('projects/list.html.twig', [
            'projects' => $this->get('developtech_agility.project_manager')->getProjects()
        ]);
    }

    /**
     * @Route("/projects/new", name="new_project", methods={"GET"})
     */
    public function newAction($form = null)
    {
        return $this->render('projects/new.html.twig', [
            'organization_types' => Organization::getTypes()
        ]);
    }

    /**
     * @Route("/projects", name="project_creation", methods={"POST"})
     */
    public function createAction(Request $request)
    {
        $connection = $this->getDoctrine()->getManager()->getConnection();
        $connection->beginTransaction();
		try {
            if (($organization = $request->request->get('organization')) !== null) {
                $organization = $this->get(OrganizationManager::class)->createOrganization($organization);
            }
			$productOwner = $this->get(UserManager::class)->createUser(
				$request->request->get('product_owner'),
				ProductOwner::TYPE_PRODUCT_OWNER,
				$organization
			);
			$project = $this->get('developtech_agility.project_manager')->createProject(
				$request->request->get('project')['name'],
				$request->request->get('project')['description'],
				$productOwner,
				$request->request->get('repository', [])
			);
            $this->get(AuthenticationManager::class)->authenticate($request, $productOwner);
			$connection->commit();
			return new JsonResponse($project, 201);
		} catch (\Exception $ex) {
			$connection->rollback();
			return new JsonResponse([
				'error' => $ex->getMessage()
			], 400);
		}
    }
    
    /**
     * @Route("/projects/{slug}", name="project_details", methods={"GET"})
     */
    public function getAction(Request $request, ProjectManager $projectManager)
    {
        $project = $this->get('developtech_agility.project_manager')->getProject($request->attributes->get('slug'));
        
        return $this->render('projects/details.html.twig', [
            'project' => $project,
            'members' => $projectManager->getProjectMembers($project),
            'membership' => ($this->isGranted('ROLE_USER')) ? $projectManager->getProjectMembership($project, $this->getUser()) : null
        ]);
    }
    
    /**
     * @Route("/projects/{slug}/join", name="project_join", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function joinAction(Request $request, ProjectManager $projectManager)
    {
        $project = $this->get('developtech_agility.project_manager')->getProject($request->attributes->get('slug'));
        
        $membership = $projectManager->joinProject($project, $this->getUser());
        
        return new JsonResponse($membership, 201);
    }
}
