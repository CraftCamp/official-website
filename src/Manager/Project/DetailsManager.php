<?php

namespace App\Manager\Project;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Project\Project;
use App\Entity\Project\Details;

class DetailsManager
{
    /** @var EntityManagerInterface **/
    protected $em;
    
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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

