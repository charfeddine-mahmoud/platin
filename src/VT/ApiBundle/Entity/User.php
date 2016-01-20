<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\UserRepository")
 */
class User implements UserInterface 
{
    /**
     * @var integer
     *
     * @ORM\Column(name="userId", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="userActive", type="boolean", options={"unsigned"=true,"default" = 1}, nullable=false)
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="userFirstName", type="string", length=45, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="userLastName", type="string", length=45, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="userEmail", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="userGender", type="string", length=255, nullable=false, columnDefinition="enum('m','f')")
     */
    private $gender;

    /**
     * @var \Date
     *
     * @ORM\Column(name="userBirthday", type="date", nullable=false)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="user__fbId", type="string", length=255, nullable=true)
     */
    private $fbId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="userCreationDate", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var float
     *
     * @ORM\Column(name="userScore", type="float")
     */
    private $score;

    /**
     * @var integer
     *
     * @ORM\Column(name="userOffline", type="boolean", nullable=false, options={"unsigned"=true,"default" = 0})
     */
    private $offline;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="userLastUpdate", type="datetime", nullable=false)
     */
    protected $lastUpdate;

    public function __construct() {
        $this->setCreationDate(new \DateTime());
        $this->setScore(0);
        $this->setOffline(0);
        $this->setActive(1);
        $this->setLastUpdate(new \DateTime());
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set fbId
     *
     * @param string $fbId
     *
     * @return User
     */
    public function setFbId($fbId)
    {
        $this->fbId = $fbId;

        return $this;
    }

    /**
     * Get fbId
     *
     * @return string
     */
    public function getFbId()
    {
        return $this->fbId;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return User
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set score
     *
     * @param float $score
     *
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set offline
     *
     * @param boolean $offline
     *
     * @return User
     */
    public function setOffline($offline)
    {
        $this->offline = $offline;

        return $this;
    }

    /**
     * Get offline
     *
     * @return boolean
     */
    public function getOffline()
    {
        return $this->offline;
    }

    /**
     * Get lastUpdate
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set lastUpdate
     *
     * @param \DateTime $lastUpdate
     * @return User
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /** {@inheritdoc} */
    public function getRoles() {
        return array('ROLE_USER');
    }
    //=============== UNUSED, BUT DO NOT REMOVE =============//

    public function getUsername() {
        return $this->getFbId();
    }

    /** @inheritdoc} */
    public function getPassword() {
        return '';
    }


    /** {@inheritdoc} */
    public function eraseCredentials() {
        
    }

    public function getSalt() {
        return '';
    }

    //=============== END UNUSED =============//
}

