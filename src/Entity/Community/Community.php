<?php

namespace App\Entity\Community;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Picture;

/**
 * @ORM\Entity()
 * @ORM\Table(name="communities__community")
 * @ORM\HasLifecycleCallbacks
 */
class Community implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     **/
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     **/
    protected $name;
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $slug;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Picture", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     **/
    protected $picture;
    /**
     * @ORM\Column(type="datetime")
     **/
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     **/
    protected $updatedAt;
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * @param int $id
     * @return \Community
     */
    public function setId(int $id): Community
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param string $name
     * @return Community
     */
    public function setName(string $name): Community
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $slug
     * @return Community
     */
    public function setSlug(string $slug): Community
    {
        $this->slug = $slug;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
    
    /**
     * @param Picture $picture
     * @return Community
     */
    public function setPicture(Picture $picture): Community
    {
        $this->picture = $picture;
        
        return $this;
    }
    
    /**
     * @return Picture
     */
    public function getPicture(): ?Picture
    {
        return $this->picture;
    }
    
    /**
     * @param \DateTime $createdAt
     * @return Community
     */
    public function setCreatedAt(\DateTime $createdAt): Community
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $updatedAt
     * @return Community
     */
    public function setUpdatedAt(\DateTime $updatedAt): Community
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
    
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'picture' => $this->picture,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}