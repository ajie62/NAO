<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 16/04/2018
 * Time: 10:01
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"mail"}, message="Cette adresse mail est déjà utilisée.")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements AdvancedUserInterface
{
    const PARTICULIER = "ROLE_PARTICULIER";
    const NATURALISTE = "ROLE_NATURALISTE";
    const ADMIN = "ROLE_ADMIN";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string")
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Observation", mappedBy="user", cascade={"persist"})
     */
    private $observations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user" ,cascade={"persist"})
     */
    private $articles;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $subscribedAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = [self::PARTICULIER];
        $this->observations = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->isActive = true;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
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
     * @param $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObservations()
    {
        return $this->observations;
    }

    /**
     * @param mixed $observations
     * @return User
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @param mixed $articles
     * @return User
     */
    public function setArticles($articles)
    {
        $this->articles = $articles;
        return $this;
    }

    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubscribedAt()
    {
        return $this->subscribedAt;
    }

    /**
     * @param mixed $subscribedAt
     * @return User
     */
    public function setSubscribedAt($subscribedAt)
    {
        $this->subscribedAt = $subscribedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setSubscribedAt(new \DateTime());
    }

    /**
     * Checks whether the user's account has expired.
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     */
    public function getUsername()
    {
        return $this->mail;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
    }
}