<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 27/03/2018
 * Time: 17:22
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Observation
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ObservationRepository")
 * @ORM\Table(name="observation")
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
     * @ORM\Column(type="string")
     */
    private $species;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $birdNumber;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $flightDirection;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deceased;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $deathCause;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $atlasCode;

    /**
     * @ORM\Column(type="text", nullable=true)
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
     * Observation constructor.
     */
    public function __construct()
    {
        $this->observedAt = new \DateTime();
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
    public function getBirdNumber()
    {
        return $this->birdNumber;
    }

    /**
     * @param mixed $birdNumber
     * @return Observation
     */
    public function setBirdNumber($birdNumber)
    {
        $this->birdNumber = $birdNumber;
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
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     * @return Observation
     */
    public function setAge($age)
    {
        $this->age = $age;
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
}
