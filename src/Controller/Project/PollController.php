<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\{
    Request,
    JsonResponse
};

use Symfony\Component\HttpKernel\Exception\{
    AccessDeniedHttpException,
    BadRequestHttpException,
    NotFoundHttpException
};

use App\Manager\Project\{
    DetailsManager,
    PollManager,
    ProjectManager,
    VoteManager
};

class PollController extends Controller
{
    /**
     * @Route("/projects/{slug}/polls", name="create_project_poll", methods={"POST"})
     */
    public function createPoll(ProjectManager $projectManager, DetailsManager $detailsManager, PollManager $pollManager, string $slug)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
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
            'slug' => $slug,
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
        if (($poll = $pollManager->get($id)) === null) {
            throw new NotFoundHttpException('projects.votes.not_found');
        }
        return $this->render('projects/poll.html.twig', [
            'poll' => $poll,
            'details' => $detailsManager->getCurrentProjectDetails($project)
        ]);
    }
    
    /**
     * @Route("/projects/{slug}/polls/{id}/vote", name="vote_project_poll", methods={"POST"})
     */
    public function vote(PollManager $pollManager, VoteManager $voteManager, Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (($poll = $pollManager->get($id)) === null) {
            throw new NotFoundHttpException('projects.votes.not_found');
        }
        return new JsonResponse($voteManager->vote(
            $poll,
            $this->getUser(),
            $request->request->get('is_positive'),
            $request->request->get('choice')
        ), 201);
    }
}