<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Project\Project;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users__product_owner")
 */
class ProductOwner extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project\Project", mappedBy="productOwner")
     */
    protected $projects;
    
    public function __construct()
    {
        parent::__construct();
        $this->projects = new ArrayCollection();
    }
    
    public function getType(): string
    {
        return self::TYPE_PRODUCT_OWNER;
    }
    
    public function addProject(Project $project): ProductOwner
    {
        $this->projects->add($project);
        
        return $this;
    }
    
    public function getProjects(): Collection
    {
        return $this->projects;
    }
}
