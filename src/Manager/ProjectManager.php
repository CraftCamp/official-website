<?php

namespace App\Manager;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\Entity\Project\Project;
use App\Entity\Project\Member;
use App\Entity\User\User;

use App\Event\Project\NewMemberEvent;

class ProjectManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var EventDispatcherInterface **/
    protected $eventDispatcher;
    
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    public function countAll(): int
    {
        return $this->em->getRepository(Project::class)->countAll();
    }
    
    public function joinProject(Project $project, User $user, bool $isNews = true): Member
    {
        if ($this->getProjectMember($project, $user) !== null) {
            throw new BadRequestHttpException('projects.already_joined');
        }
        $membership =
            (new Member())
            ->setUser($user)
            ->setProject($project)
            ->setIsActive(true)
        ;
        $this->em->persist($membership);
        $this->em->flush($membership);
        if ($isNews === true) {
            $this->eventDispatcher->dispatch(NewMemberEvent::NAME, new NewMemberEvent($project, $user));
        }
        return $membership;
    }
    
    public function getProjectMembers(Project $project): array
    {
        return $this->em->getRepository(Member::class)->findByProject($project);
    }
    
    public function getProjectMember(Project $project, User $user): ?Member
    {
        return $this->em->getRepository(Member::class)->findOneBy([
            'project' => $project,
            'user' => $user
        ]);
    }
}