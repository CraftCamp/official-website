<?php

namespace App\Manager;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Project\Project;
use App\Entity\Project\Member;
use App\Entity\User\User;

class ProjectManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function countAll(): int
    {
        return $this->em->getRepository(Project::class)->countAll();
    }
    
    public function joinProject(Project $project, User $user): Member
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