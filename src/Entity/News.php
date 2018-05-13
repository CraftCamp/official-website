<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 * @ORM\Table(name="news")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=2)
 * @ORM\DiscriminatorMap({
 *     "CO" = "App\Entity\Community\News",
 *     "PR" = "App\Entity\Project\News"
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $category;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    
    const TYPE_COMMUNITY = 'CO';
    const TYPE_PROJECT = 'PR';
    
    abstract public function getType(): string;
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
    
    public function setId(int $id): News
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function setCategory(string $category): News
    {
        $this->category = $category;
        
        return $this;
    }
    
    public function getCategory(): string
    {
        return $this->category;
    }
    
    public function setTitle(string $title): News
    {
        $this->title = $title;
        
        return $this;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function setCreatedAt(\DateTime $createdAt): News
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}