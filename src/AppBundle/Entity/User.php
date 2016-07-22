<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class User implements UserInterface {
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
     * @ORM\Column(type="string", length=65)
     **/
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=65, unique=true)
     */
    protected $email;

    /** @var string **/
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=140)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=140)
     */
    protected $salt;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     **/
    protected $roles;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     **/
    protected $isEnabled;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     **/
    protected $isLocked;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     **/
    protected $createdAt;

    /**
     * @var \datetime
     *
     * @ORM\Column(type="datetime")
     **/
    protected $updatedAt;

    /**
     * @param integer $id
     * @return User
     */
    public function setId($id)
    {
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
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $role
     * @return User
     */
    public function addRole($role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return boolean
     */
    public function eraseCredentials()
    {
        return true;
    }

    /**
     * @param boolean $isEnabled
     * @return User
     */
    public function enable($isEnabled) {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsEnabled() {
        return $this->isEnabled;
    }

    /**
     * @param boolean $isLocked
     * @return User
     */
    public function setIsLocked($isLocked)
    {
        $this->isLocked = $isLocked;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsLocked()
    {
        return $this->isLocked;
    }

    /**
    * @param \DateTime $createdAt
    * @return User
    */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
    * @param \DateTime $updatedAt
    * @return User
    */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
