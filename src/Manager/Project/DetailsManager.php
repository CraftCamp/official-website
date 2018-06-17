<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Project\Project;
use App\Entity\Project\Details;

class DetailsManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    /** @var SessionInterface **/
    protected $session;
    
    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;
    }
    
    public function getProjectDetails(Project $project)
    {
        return $this->em->getRepository(Details::class)->findOneBy([
            'project' => $project
        ]);
    }
    
    public function putProjectDetails(Project $project, array $data): Details
    {
        if (($details = $this->getProjectDetails($project)) === null) {
            $details = (new Details())->setProject($project);
            $this->em->persist($details);
            $this->session->getFlashbag()->add('success', 'projects.descriptions.first_completed');
        }
        $details
            ->setNeedDescription($data['need_description'])
            ->setTargetDescription($data['target_description'])
            ->setGoalDescription($data['goal_description'])
        ;
        $this->em->flush();
        return $details;
    }
}

