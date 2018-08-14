<?php

namespace App\Controller\Project;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\{
    Request,
    Response,
    JsonResponse
};

use Symfony\Component\HttpKernel\Exception\{
    UnauthorizedHttpException,
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
    public function create(ProjectManager $projectManager, DetailsManager $detailsManager, PollManager $pollManager, string $slug)
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
        $poll = $pollManager->create($project, $details);
        return $this->redirectToRoute('get_poll', [
            'slug' => $slug,
            'id' => $poll->getId()
        ]);
    }
    
    /**
     * @Route("/projects/{slug}/polls/{id}", name="get_poll", methods={"GET"})
     */
    public function find(ProjectManager $projectManager, PollManager $pollManager, DetailsManager $detailsManager, VoteManager $voteManager, string $slug, int $id)
    {
        if (($project = $projectManager->get($slug)) === null) {
            throw new NotFoundHttpException('projects.not_found');
        }
        if (($poll = $pollManager->get($id)) === null) {
            throw new NotFoundHttpException('projects.votes.not_found');
        }
        return $this->render('projects/poll.html.twig', [
            'poll' => $poll,
            'has_already_voted' => $this->getUser() !== null && $voteManager->getUserVote($poll, $this->getUser()) !== null,
            'details' => $detailsManager->getCurrentProjectDetails($project),
            'votes' => ($this->isGranted('ROLE_USER') && $this->getUser()->getProjects()->contains($project)) ? $voteManager->getPollVotes($poll) : [],
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
        if ($poll->getProject()->getProductOwner() === $this->getUser()) {
            throw new AccessDeniedHttpException('projects.votes.product_owner_cannot_vote');
        }
        return new JsonResponse($voteManager->vote(
            $poll,
            $this->getUser(),
            (bool) $request->request->get('is_positive'),
            $request->request->get('choice')
        ), 201);
    }
    
    /**
     * @Route("/projects/{slug}/polls/{id}/close", name="close_project_poll", methods={"PUT"})
     */
    public function close(PollManager $pollManager, VoteManager $voteManager, Request $request, int $id)
    {
        if ($request->getHttpHost() !== 'craftcamp_website') {
            throw new UnauthorizedHttpException('projects.access_denied');
        }
        if (($poll = $pollManager->get($id)) === null) {
            throw new NotFoundHttpException('projects.votes.not_found');
        }
        $pollManager->processResults($poll, $voteManager->getPollVotes($poll));
        return new Response('', 204);
    }
}