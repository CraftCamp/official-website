<?php

namespace App\Model\Project;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Vote
{
    /** @var Poll **/
    protected $poll;
    /** @var UserInterface **/
    protected $user;
    /** @var string **/
    protected $option;
    /** @var bool **/
    protected $isPositive;
    /** @var \DateTime **/
    protected $createdAt;
    
    const OPTION_POSITIVE_TECH = 'positive.tech';
    const OPTION_POSITIVE_PURPOSE = 'positive.purpose';
    
    const OPTION_NEGATIVE_TECH = 'negative.tech';
    const OPTION_NEGATIVE_PURPOSE = 'negative.purpose';
    const OPTION_NEGATIVE_INFOS = 'negative.infos';
    
    public function setPoll(Poll $poll): Vote
    {
        $this->poll = $poll;
        
        return $this;
    }
    
    public function getPoll(): Poll
    {
        return $this->poll;
    }
    
    public function setUser(UserInterface $user): Vote
    {
        $this->user = $user;
        
        return $this;
    }
    
    public function getUser(): UserInterface
    {
        return $this->user;
    }
    
    public function setOption(string $option): Vote
    {
        $this->option = $option;
        
        return $this;
    }
    
    public function getOption(): string
    {
        return $this->option;
    }
    
    public function setIsPositive(bool $isPositive): Vote
    {
        $this->isPositive = $isPositive;
        
        return $this;
    }
    
    public function getIsPositive(): bool
    {
        return $this->isPositive;
    }
    
    public function setCreatedAt(\DateTime $createdAt): Vote
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}