<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Model\News as NewsModel;

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
abstract class News extends NewsModel
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
     * @ORM\Column(type="array")
     */
    protected $data;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    
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
}