<?php

namespace App\Model\Project;

abstract class Repository
{
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $slug;
	/** @var Project **/
	protected $project;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;
	
	const TYPE_GITHUB = 'github';
    const TYPE_GITLAB = 'gitlab';
	
	abstract public function getType(): string;
	
    public function setName(string $name): Repository
    {
        $this->name = $name;
        
        return $this;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setSlug(string $slug): Repository
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    public function getSlug(): string
    {
        return $this->slug;
    }
    
	public function setProject(Project $project): Repository
	{
		$this->project = $project;
		
		return $this;
	}
	
	public function getProject(): Project
	{
		return $this->project;
	}
    
    public function setCreatedAt(\DateTime $createdAt): Repository
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    public function setUpdatedAt(\DateTime $updatedAt): Repository
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}