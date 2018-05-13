<?php

namespace App\Entity\Community;

use App\Entity\News as NewsModel;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Community\NewsRepository")
 * @ORM\Table(name="communities__news")
 */
class News extends NewsModel
{
    /**
     * @ORM\ManyToOne(targetEntity="Community")
     */
    protected $community;
    
    const CATEGORY_COMMUNITY_CREATION = 'community_creation';
    const CATEGORY_NEW_MEMBER = 'new_member';
    const CATEGORY_NEW_PROJECT = 'new_project';
    
    public function getType(): string
    {
        return self::TYPE_COMMUNITY;
    }
    
    public function setCommunity(Community $community): News
    {
        $this->community = $community;
        
        return $this;
    }
    
    public function getCommunity(): Community
    {
        return $this->community;
    }
}