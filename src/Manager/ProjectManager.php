<?php

namespace App\Manager;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Project\Project;
use App\Entity\Project\Membership;
use App\Entity\User\User;

class ProjectManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * @return int
     */
    public function countAll()
    {
        return $this->em->getRepository(Project::class)->countAll();
    }
    
    /**
     * @param Project $project
     * @param User $user
     * @return Membership
     */
    public function joinProject(Project $project, User $user)
    {
        if ($this->getProjectMembership($project, $user) !== null) {
            throw new BadRequestHttpException('projects.already_joined');
        }
        $membership =
            (new Membership())
            ->setUser($user)
            ->setProject($project)
            ->setIsActive(true)
        ;
        $this->em->persist($membership);
        $this->em->flush($membership);
        return $membership;
    }
    
    /**
     * @param Project $project
     * @return array
     */
    public function getProjectMembers(Project $project)
    {
        return $this->em->getRepository(Membership::class)->findByProject($project);
    }
    
    /**
     * @param Project $project
     * @param User $user
     * @return Membership
     */
    public function getProjectMembership(Project $project, User $user)
    {
        return $this->em->getRepository(Membership::class)->findOneBy([
            'project' => $project,
            'user' => $user
        ]);
    }
}