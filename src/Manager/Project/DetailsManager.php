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
    
    public function getCurrentProjectDetails(Project $project)
    {
        $results = $this->em->getRepository(Details::class)->findBy([
            'project' => $project
        ], [
            'updatedAt' => 'DESC'
        ], 1);
        return (count($results) > 0) ? $results[0] : null;
    }
    
    public function putProjectDetails(Project $project, array $data): Details
    {
        if (($details = $this->getCurrentProjectDetails($project)) === null) {
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

