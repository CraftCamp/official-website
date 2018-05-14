<?php

namespace App\Entity\Project;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="project__repositories")
 * @ORM\HasLifecycleCallbacks
 */
class Repository
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Project")
     */
    protected $project;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;
}