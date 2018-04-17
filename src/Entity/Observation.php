<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 27/03/2018
 * Time: 17:22
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as obsAssert;

/**
 * Class Observation
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ObservationRepository")
 * @ORM\Table(name="observation")
 * @ORM\HasLifecycleCallbacks()
 */
class Observation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Species", inversedBy="observations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @obsAssert\IsValidSpecies()
     */
    private $species;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Aucune position n'a été fournie.")
     * @Assert\Regex("/^(\-?\d+(\.\d+)?)\s*(\-?\d+(\.\d+)?)$/")
     */
    private $longitude;

    /**
     * @ORM\Column(type="float")
     * @Assert\Regex("/^(\-?\d+(\.\d+)?)\s*(\-?\d+(\.\d+)?)$/")
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $flightDirection;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deceased;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @obsAssert\IsDeceased()
     */
    private $deathCause;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Valid()
     */
    private $atlasCode;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(max="400", maxMessage="{{ limit }} caractères maximum.")
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $observedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     * @obsAssert\HasImage()
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="observations")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    /**
     * Observation constructor.
     */
    public function __construct()
    {
        $this->species = new ArrayCollection();
        $this->validated = false;
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
     * @return Observation
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * @param mixed $species
     * @return Observation
     */
    public function setSpecies($species)
    {
        $this->species = $species;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return Observation
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFlightDirection()
    {
        return $this->flightDirection;
    }

    /**
     * @param mixed $flightDirection
     * @return Observation
     */
    public function setFlightDirection($flightDirection)
    {
        $this->flightDirection = $flightDirection;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $sex
     * @return Observation
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeceased()
    {
        return $this->deceased;
    }

    /**
     * @param mixed $deceased
     * @return Observation
     */
    public function setDeceased($deceased)
    {
        $this->deceased = $deceased;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDeathCause()
    {
        return $this->deathCause;
    }

    /**
     * @param mixed $deathCause
     * @return Observation
     */
    public function setDeathCause($deathCause)
    {
        $this->deathCause = $deathCause;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAtlasCode()
    {
        return $this->atlasCode;
    }

    /**
     * @param mixed $atlasCode
     * @return Observation
     */
    public function setAtlasCode($atlasCode)
    {
        $this->atlasCode = $atlasCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     * @return Observation
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getObservedAt()
    {
        return $this->observedAt;
    }

    /**
     * @param mixed $observedAt
     * @return Observation
     */
    public function setObservedAt($observedAt)
    {
        $this->observedAt = $observedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return Observation
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preObsSubmit()
    {
        if ($this->getDeceased()) {
            $this->setFlightDirection(null);
        }
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     * @return Observation
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Observation
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     * @return Observation
     */
    public function setValidated(bool $validated): Observation
    {
        $this->validated = $validated;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->setObservedAt(new \DateTime());
    }
}
