<?php

namespace App\Entity\Project;

use App\Entity\News as NewsModel;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Project\NewsRepository")
 * @ORM\Table(name="project__news")
 */
class News extends NewsModel
{
    /**
     * @ORM\ManyToOne(targetEntity="project")
     */
    protected $project;
    
    const CATEGORY_ACCEPTED_POLL = 'accepted_poll';
    const CATEGORY_NEW_COMMUNITY = 'new_community';
    const CATEGORY_NEW_MEMBER = 'new_member';
    const CATEGORY_NEW_RELEASE = 'new_release';
    const CATEGORY_NEW_REPOSITORY = 'new_repository';
    
    public function getType(): string
    {
        return self::TYPE_PROJECT;
    }
    
    public function setProject(Project $project): News
    {
        $this->project = $project;
        
        return $this;
    }
    
    public function getProject(): Project
    {
        return $this->project;
    }
}