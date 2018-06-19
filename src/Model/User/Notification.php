<?php

namespace App\Model\User;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Notification
{
    /** @var UserInterface **/
    protected $user;
    /** @var string **/
    protected $content;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $readAt;
    
    public function setUser(UserInterface $user): Notification
    {
        $this->user = $user;
        
        return $this;
    }
    
    public function getUser(): UserInterface
    {
        return $this->user;
    }
    
    public function setContent(string $content): Notification
    {
        $this->content = $content;
        
        return $this;
    }
    
    public function getContent(): string
    {
        return $this->content;
    }
    
    public function setCreatedAt(\DateTime $createdAt): Notification
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    public function setReadAt(\DateTime $readAt): Notification
    {
        $this->readAt = $readAt;
        
        return $this;
    }
    
    public function getReadAt(): \DateTime
    {
        return $this->readAt;
    }
}