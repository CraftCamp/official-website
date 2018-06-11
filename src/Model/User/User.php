<?php

namespace App\Model\User;

use Symfony\Component\Security\Core\User\UserInterface;
use App\Model\Organization;
use Doctrine\Common\Collections\ArrayCollection;

abstract class User implements UserInterface, \JsonSerializable
{
    /** @var string **/
    protected $username;
    /** @var string **/
    protected $email;
    /** @var string **/
    protected $plainPassword;
    /** @var string **/
    protected $password;
    /** @var string **/
    protected $salt;
    /** @var ArrayCollection **/
    protected $roles;
    /** @var boolean **/
    protected $isEnabled;
    /** @var boolean **/
    protected $isLocked;
    /** @var ArrayCollection **/
    protected $organizations;
    /** @var ActivationLink **/
    protected $activationLink;
    /** @var \DateTime **/
    protected $createdAt;
    /** @var \DateTime **/
    protected $updatedAt;

    const TYPE_MEMBER = 'ME';
    const TYPE_PRODUCT_OWNER = 'PO';
    const TYPE_BETA_TESTER = 'BT';

    abstract public function getType(): string;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPlainPassword(string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPassword(string$password):  User
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setSalt(string $salt): User
    {
        $this->salt = $salt;

        return $this;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function addRole(string $role): User
    {
        $this->roles->add($role);

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains($role);
    }

    public function removeRole(string $role): User
    {
        if($this->hasRole($role)) {
            $this->roles->removeElement($role);
        }
        return $this;
    }
    
    public function setRoles(array $roles): User
    {
        $this->roles = new ArrayCollection($roles);
        
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles->toArray();
    }

    public function eraseCredentials(): bool
    {
        return true;
    }

    public function enable(bool $isEnabled): User
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsLocked(bool $isLocked): User
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    public function getIsLocked(): bool
    {
        return $this->isLocked;
    }

    public function addOrganization(Organization $organization): User
    {
        $this->organizations->add($organization);

        return $this;
    }
    
    public function removeOrganization(Organization $organization): User
    {
        $this->organizations->removeElement($organization);
        
        return $this;
    }
    
    public function hasOrganization(Organization $organization): bool
    {
        return $this->organizations->contains($organization);
    }

    public function getOrganizations(): ArrayCollection
    {
        return $this->organizations;
    }

    public function setActivationLink(ActivationLink $activationLink): User
    {
        $this->activationLink = $activationLink;

        return $this;
    }

    public function getActivationLink(): ?ActivationLink
    {
        return $this->activationLink;
    }

    public function setCreatedAt(\DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): User
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
        return  [
            'id' => $this->id,
            'username' => $this->username,
            'type' => $this->getType(),
            'organizations' => $this->organizations,
            'roles' => $this->roles,
            'is_enabled' => $this->isEnabled,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}