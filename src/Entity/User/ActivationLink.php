<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;

use App\Model\User\ActivationLink as ActivationLinkModel;

/**
 * @ORM\Table(name="users__activation_link")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 */
class ActivationLink extends ActivationLinkModel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     **/
    protected $id;
    /**
     * @ORM\Column(type="string", length=80, unique=true)
     **/
    protected $hash;
    /**
     * @ORM\Column(name="created_at", type="datetime")
     **/
    protected $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    public function setId(int $id): ActivationLink
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
