<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Organization;
use App\Entity\User\ProductOwner;

use App\Manager\OrganizationManager;
use App\Manager\UserManager;

use App\Security\Authentication\AuthenticationManager;

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
			$organization = $this->get(OrganizationManager::class)->createOrganization($request->request->get('organization'));
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
}
