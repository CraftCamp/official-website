<?php

namespace App\Entity\User;

use App\Model\User\User as UserModel;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\Table(name="users__user")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=2)
 * @ORM\DiscriminatorMap({
 *     "ME" = "Member",
 *     "PO" = "ProductOwner",
 *     "BT" = "BetaTester"
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class User extends UserModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=65)
     **/
    protected $username;
    /**
     * @ORM\Column(type="string", length=65, unique=true)
     */
    protected $email;
    /**
     * @ORM\Column(type="string", length=140)
     */
    protected $password;
    /**
     * @ORM\Column(type="string", length=140)
     */
    protected $salt;
    /**
     * @ORM\Column(type="array")
     **/
    protected $roles;
    /**
     * @ORM\Column(type="boolean")
     **/
    protected $isEnabled;
    /**
     * @ORM\Column(type="boolean")
     **/
    protected $isLocked;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Organization")
     * @ORM\JoinTable(name="users__organizations")
     */
    protected $organizations;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project\Project", mappedBy="productOwner")
     */
    protected $projects;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User\ActivationLink", cascade={"remove"})
     */
    protected $activationLink;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $githubId;
    /**
     * @ORM\Column(type="string", length=140, nullable=true)
     */
    protected $githubAccessToken;
    /**
     * @ORM\Column(type="datetime")
     **/
    protected $createdAt;
    /**
     * @ORM\Column(type="datetime")
     **/
    protected $updatedAt;

    const TYPE_MEMBER = 'ME';
    const TYPE_PRODUCT_OWNER = 'PO';
    const TYPE_BETA_TESTER = 'BT';
    
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

    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
