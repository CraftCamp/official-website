<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
    NotFoundHttpException
};

use App\Manager\Project\{
    DetailsManager,
    PollManager,
    ProjectManager
};

class PollController extends Controller
{
    /**
     * @Route("/projects/{slug}/polls", name="create_project_poll", methods={"POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function createPoll(ProjectManager $projectManager, DetailsManager $detailsManager, PollManager $pollManager, string $slug)
    {
        if (($project = $projectManager->get($slug)) === null) {
            throw new NotFoundHttpException('projects.not_found');
        }
        if (!$this->getUser()->getProjects()->contains($project)) {
            throw new AccessDeniedHttpException('projects.access_denied');
        }
        if (($details = $detailsManager->getCurrentProjectDetails($project)) === null) {
            throw new BadRequestHttpException('projects.polls.not_ready');
        }
        $poll = $pollManager->createPoll($project, $details);
        return $this->redirectToRoute('get_poll', [
            'id' => $poll->getId()
        ]);
    }
    
    /**
     * @Route("/projects/{slug}/polls/{id}", name="get_poll", methods={"GET"})
     */
    public function getPoll(ProjectManager $projectManager, PollManager $pollManager, DetailsManager $detailsManager, string $slug, int $id)
    {
        if (($project = $projectManager->get($slug)) === null) {
            throw new NotFoundHttpException('projects.not_found');
        }
        return $this->render('projects/poll.html.twig', [
            'poll' => $pollManager->get($id),
            'details' => $detailsManager->getCurrentProjectDetails($project)
        ]);
    }
}