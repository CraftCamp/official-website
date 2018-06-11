<?php

namespace App\Model;

abstract class Organization implements \JsonSerializable
{
    /** @var string **/
    protected $name;
    /** @var string **/
    protected $slug;
    /** @var string **/
    protected $type;
    /** @var string **/
    protected $description;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;
    
    const TYPE_ASSOCIATION = 'association';
    const TYPE_SMALL_COMPANY = 'small_company';
    const TYPE_MEDIUM_COMPANY = 'medium_company';

    public static function getTypes(): array
    {
        return [
            self::TYPE_ASSOCIATION,
            self::TYPE_SMALL_COMPANY,
            self::TYPE_MEDIUM_COMPANY
        ];
    }
    
    public function setName(string $name): Organization
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setSlug(string $slug): Organization
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
    
    public function setType(string $type): Organization
    {
        $this->type = $type;
        
        return $this;
    }
    
    public function getType(): string
    {
        return $this->type;
    }

    public function setDescription(string $description): Organization
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setCreatedAt(\DateTime $createdAt): Organization
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): Organization
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
    
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'type' => $this->type,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}