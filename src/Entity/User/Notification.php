<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

use App\Model\User\Notification as NotificationModel;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\NotificationRepository")
 * @ORM\Table(name="users__notifications")
 * @ORM\HasLifecycleCallbacks
 */
class Notification extends NotificationModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $content;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $readAt;
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function setId(int $id): Notification
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
}