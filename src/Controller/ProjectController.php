<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Organization;
use App\Entity\User\ProductOwner;

use App\Manager\OrganizationManager;
use App\Manager\UserManager;

use App\Security\Authentication\AuthenticationManager;

use App\Manager\Project\{
    DetailsManager,
    NewsManager,
    PollManager,
    ProjectManager
};
use App\Registry\ProjectRegistry;

class ProjectController extends Controller
{
    /**
     * @Route("/projects", name="projects_list", methods={"GET"})
     */
    public function getListAction(ProjectManager $projectManager, ProjectRegistry $projectRegistry)
    {
        $projectRegistry->store($projectManager->getAll());
        return $this->render('projects/list.html.twig', [
            'projects' => $projectRegistry->getItems()
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
    public function createAction(Request $request, ProjectManager $projectManager, AuthenticationManager $authenticationManager, UserManager $userManager, OrganizationManager $organizationManager)
    {
        $connection = $this->getDoctrine()->getManager()->getConnection();
        $connection->beginTransaction();
		try {
            if (($organization = $request->request->get('organization')) !== null) {
                $organization = $organizationManager->createOrganization($organization);
            }
            if (($productOwnerData = $request->request->get('product_owner')) === null && !$this->isGranted('ROLE_USER')) {
                throw new BadRequestHttpException('projects.missing_product_owner');
            }
			$productOwner = ($productOwnerData !== null) ? $userManager->createUser(
				$request->request->get('product_owner'),
				ProductOwner::TYPE_PRODUCT_OWNER,
				$organization
			) : $this->getUser();
            $project = $projectManager->createProject(
                $request->request->get('project')['name'],
                $request->request->get('project')['description'],
                $productOwner,
                $organization
            );
            if ($organization !== null && !$productOwner->hasOrganization($organization)) {
                $productOwner->addOrganization($organization);
            }
            $projectManager->joinProject($project, $productOwner, false);
            $authenticationManager->authenticate($request, $productOwner);
			$connection->commit();
			return new JsonResponse($project, 201);
		} catch (\Exception $ex) {
			$connection->rollback();
			throw $ex;
		}
    }
    
    /**
     * @Route("/projects/{slug}", name="project_page", methods={"GET"})
     */
    public function getAction(Request $request, ProjectManager $projectManager, NewsManager $newsManager)
    {
        $project = $projectManager->get($request->attributes->get('slug'));
        
        return $this->render('projects/details.html.twig', [
            'project' => $project,
            'news' => $newsManager->getProjectNews($project),
            'members' => $projectManager->getProjectMembers($project),
            'membership' => ($this->isGranted('ROLE_USER')) ? $projectManager->getProjectMember($project, $this->getUser()) : null
        ]);
    }
    
    /**
     * @Route("/projects/{slug}/workspace", name="project_workspace", methods={"GET"})
     */
    public function getWorkspaceAction(Request $request, ProjectManager $projectManager, DetailsManager $detailsManager, PollManager $pollManager)
    {
        $project = $projectManager->get($request->attributes->get('slug'));
        return $this->render('projects/workspace.html.twig', [
            'project' => $project,
            'details' => $detailsManager->getCurrentProjectDetails($project),
            'poll' => $pollManager->getCurrentProjectPoll($project),
        ]);
    }
    
    /**
     * @Route("/projects/{slug}/details", name="project_details", methods={"GET"})
     */
    public function getDetailsAction(Request $request, ProjectManager $projectManager, DetailsManager $detailsManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $project = $projectManager->get($request->attributes->get('slug'));
        $user = $this->getUser();
        if (!$user->getProjects()->contains($project)) {
            throw new AccessDeniedHttpException('projects.access_denied');
        }
        return $this->render('projects/details_edition.html.twig', [
            'project' => $project,
            'details' => $detailsManager->getCurrentProjectDetails($project)
        ]);
    }
    
    /**
     * @Route("/projects/{slug}/details", name="put_project_details", methods={"PUT"})
     */
    public function putDetailsAction(Request $request, ProjectManager $projectManager, DetailsManager $detailsManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $project = $projectManager->get($request->attributes->get('slug'));
        $user = $this->getUser();
        if (!$user->getProjects()->contains($project)) {
            throw new AccessDeniedHttpException('projects.access_denied');
        }
        $details = $detailsManager->putProjectDetails($project, $request->request->all());
        return new JsonResponse($details, ($details->getCreatedAt() === $details->getUpdatedAt()) ? 201 : 200);
    }
    
    /**
     * @Route("/projects/{slug}/join", name="project_join", methods={"POST"})
     */
    public function joinAction(Request $request, ProjectManager $projectManager)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $project = $projectManager->get($request->attributes->get('slug'));
        
        $membership = $projectManager->joinProject($project, $this->getUser());
        
        return new JsonResponse($membership, 201);
    }
}
