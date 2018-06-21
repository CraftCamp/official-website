<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

use App\Model\Project\Poll as PollModel;

/**
 * @ORM\Table(name="project__polls")
 * @ORM\Entity()
 */
class Poll extends PollModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $project;
    /**
     * @ORM\OneToOne(targetEntity="Details")
     */
    protected $details;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isEnded;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $endedAt;
    
    public function setId(int $id): Poll
    {
        $this->id = $id;
        
        return $this;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
}