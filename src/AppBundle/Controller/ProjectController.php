<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Organization;
use AppBundle\Entity\User\ProductOwner;

class ProjectController extends Controller {

    /**
     * @Route("/projects", name="projects_list", methods={"GET"})
     */
    public function getListAction() {
        return $this->render('projects/list.html.twig', [
            'projects' => $this->get('developtech_agility.project_manager')->getProjects()
        ]);
    }

    /**
     * @Route("/projects/new", name="new_project", methods={"GET"})
     */
    public function newAction($form = null) {
        return $this->render('projects/new.html.twig', [
            'organization_types' => Organization::getTypes()
        ]);
    }

    /**
     * @Route("/projects", name="project_creation", methods={"POST"})
     */
    public function createAction(Request $request) {
		$data = json_decode($request->getContent(), true);
		
        $connection = $this->getDoctrine()->getManager()->getConnection();
        $connection->beginTransaction();
		try {
			$organization = $this->get('developtech.organization_manager')->createOrganization($data['organization']);
			$productOwner = $this->get('developtech.user_manager')->createUser(
				$data['product_owner'],
				ProductOwner::TYPE_PRODUCT_OWNER,
				$organization
			);
			$project = $this->get('developtech_agility.project_manager')->createProject(
				$data['project']['name'],
				$data['project']['description'],
				$productOwner,
				$data['repository'] ?? []
			);
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
