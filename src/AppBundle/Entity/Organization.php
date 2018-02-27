<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 */
class Organization {
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=125)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=125)
     */
    protected $slug;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=25) 
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
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
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @param int $id
     * @return Organization
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Organization
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $slug
     * @return Organization
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug() {
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

    /**
     * @param string $description
     * @return Organization
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param \DateTime $createdAt
     * @return Organization
     */
    public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return Organization
     */
    public function setUpdatedAt(\DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
}
