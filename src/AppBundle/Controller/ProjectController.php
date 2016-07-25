<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;

use AppBundle\Form\ProjectType;

use AppBundle\Entity\Project;

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
            'form' => (($form !== null) ? $form : $this->createForm(ProjectType::class))->createView()
        ]);
    }

    /**
     * @Route("/projects", name="project_creation", methods={"POST"})
     */
    public function createAction(Request $request) {
        $projectData = new Project();
        $form = $this->createForm(ProjectType::class, $projectData);

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $this->forward('AppBundle:Project:new', [
                'form' => $form
            ]);
        }
        $productOwner = $projectData->getProductOwner();
        $connection = $this->getDoctrine()->getManager()->getConnection();
        $connection->beginTransaction();
        $this->get('developtech.organization_manager')->createOrganization($productOwner->getOrganization());
        if (!$this->get('developtech.user_manager')->createUser($form->get('productOwner'), $productOwner)) {
            $connection->rollback();
            return $this->forward('AppBundle:Project:new', [
                'form' => $form
            ]);
        }
        $project = $this->get('developtech_agility.project_manager')->createProject($projectData->getName(), $productOwner);
        $connection->commit();
        return $this->forward('AppBundle:Project:getList');
    }
}
