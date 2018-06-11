<?php

namespace App\Manager\Project;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use App\Entity\Project\Project;
use App\Entity\Project\Member;
use App\Entity\User\User;
use App\Entity\Organization;

use App\Event\Project\NewMemberEvent;
use App\Event\Project\NewProjectEvent;

use App\Utils\Slugger;

class ProjectManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var EventDispatcherInterface **/
    protected $eventDispatcher;
    /** @var Slugger **/
    protected $slugger;
    
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, Slugger $slugger)
    {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
        $this->slugger = $slugger;
    }
    
    public function getAll(): array
    {
        return $this->em->getRepository(Project::class)->findAll();
    }
    
    public function get(string $slug)
    {
        return $this->em->getRepository(Project::class)->findOneBySlug($slug);
    }
    
    public function countAll(): int
    {
        return $this->em->getRepository(Project::class)->countAll();
    }
    
    public function createProject(string $name, string $description, User $productOwner, Organization $organization = null): Project
    {
        if ($this->em->getRepository(Project::class)->findOneByName($name) !== null) {
            throw new BadRequestHttpException('projects.existing_name');
        }
        $project =
            (new Project())
            ->setName($name)
            ->setSlug($this->slugger->slugify($name))
            ->setDescription($description)
            ->setProductOwner($productOwner)
        ;
        if ($organization !== null) {
            $project->setOrganization($organization);
        }
        $this->em->persist($project);
        $this->em->flush($project);
		
		$this->eventDispatcher->dispatch(NewProjectEvent::NAME, new NewProjectEvent($project));
        
        return $project;
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