<?php

namespace App\Model;

abstract class News
{
    /** @var string **/
    protected $category;
    /** @var array **/
    protected $data;
    /** @var \DateTime **/
    protected $createdAt;
    
    const TYPE_COMMUNITY = 'CO';
    const TYPE_PROJECT = 'PR';
    
    abstract public function getType(): string;
    
    public function setCategory(string $category): News
    {
        $this->category = $category;
        
        return $this;
    }
    
    public function getCategory(): string
    {
        return $this->category;
    }
    
    public function setData(array $data): News
    {
        $this->data = $data;
        
        return $this;
    }
    
    public function getData(): array
    {
        return $this->data;
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