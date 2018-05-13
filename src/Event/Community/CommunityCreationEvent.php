<?php

namespace App\Event\Community;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Community\Community;
use App\Entity\User\User;

class CommunityCreationEvent extends Event
{
    /** @var Community **/
    protected $community;
    /** @var User **/
    protected $founder;
    
    const NAME = 'community.creation';
    
    public function __construct(Community $community, User $founder)
    {
        $this->community = $community;
        $this->founder = $founder;
    }
    
    public function getCommunity(): Community
    {
        return $this->community;
    }
    
    public function getFounder(): User
    {
        return $this->founder;
    }
}