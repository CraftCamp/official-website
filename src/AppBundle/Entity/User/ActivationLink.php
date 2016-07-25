<?php

namespace AppBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="users__activation_link")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class ActivationLink {
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     **/
    protected $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=80, unique=true)
     **/
    protected $hash;
    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     **/
    protected $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->createdAt = new \DateTime();
    }

    /**
     * @param integer $id
     * @return \AppBundle\Entity\User\ActivationLink
     */
    public function setId(int $id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $hash
     * @return \AppBundle\Entity\User\ActivationLink
     */
    public function setHash(string $hash) {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash() {
        return $this->hash;
    }

    /**
     * @param DateTime $createdAt
     * @return \AppBundle\Entity\User\ActivationLink
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
}
