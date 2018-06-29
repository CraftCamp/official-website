<?php

namespace App\Model\Project;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Vote implements \JsonSerializable
{
    /** @var Poll **/
    protected $poll;
    /** @var UserInterface **/
    protected $user;
    /** @var string **/
    protected $choice;
    /** @var bool **/
    protected $isPositive;
    /** @var \DateTime **/
    protected $createdAt;
    
    const CHOICE_POSITIVE_TECH = 'positive.tech';
    const CHOICE_POSITIVE_PURPOSE = 'positive.purpose';
    
    const CHOICE_NEGATIVE_TECH = 'negative.tech';
    const CHOICE_NEGATIVE_PURPOSE = 'negative.purpose';
    const CHOICE_NEGATIVE_INFOS = 'negative.infos';
    
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
    
    public function setChoice(string $choice): Vote
    {
        $this->choice = $choice;
        
        return $this;
    }
    
    public function getChoice(): string
    {
        return $this->choice;
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
    
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'poll' => $this->poll,
            'user' => $this->user,
            'is_positive' => $this->isPositive,
            'choice' => $this->choice,
            'created_at' => $this->createdAt
        ];
    }
}