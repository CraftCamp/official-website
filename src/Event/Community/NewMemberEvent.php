<?php

namespace App\Event\Community;

use Symfony\Component\EventDispatcher\Event;

use App\Entity\Community\Community;
use App\Entity\User\User;

class NewMemberEvent extends Event
{
    /** @var Community **/
    protected $community;
    /** @var User **/
    protected $user;
    
    const NAME = 'community.new_member';
    
    public function __construct(Community $community, User $user)
    {
        $this->community = $community;
        $this->user = $user;
    }
    
    public function getCommunity(): Community
    {
        return $this->community;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }
}