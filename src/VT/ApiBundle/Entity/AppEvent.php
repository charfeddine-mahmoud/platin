<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CrEOF\Spatial\PHP\Types\Geometry\Point;

/**
 * AppEvent
 *
 * @ORM\Table(name="appevent")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\AppEventRepository")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="appeventType", type="string", columnDefinition="enum('UNKNOWN','USER')")
 * @ORM\DiscriminatorMap({"UNKNOWN" = "AppEvent", "USER" = "AppEventUser"})
 */
class AppEvent
{
    /**
     * @var integer
     *
     * @ORM\Column(name="appeventId", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * Uniq identifier generated on the mobile side
     * 
     * @var string
     * 
     * @ORM\Column(name="appeventIdentifier", type="string", nullable=true)
     */
    private $identifier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="appeventCreationDate", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="appeventRecordDate", type="datetime", nullable=false)
     */
    private $recordDate;

    /**
     * @var Point
     *
     * @ORM\Column(name="appeventLocation", type="point", nullable=true)
     */
    private $location;


    /**
     * @var \VT\ApiBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\User", inversedBy="appEvents")
     * @ORM\JoinColumn(name="appevent_userId", referencedColumnName="userId")
     */
    private $user;


    public function __construct()
    {
        $this->setRecordDate(new \DateTime());
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
     * Set identifier
     *
     * @param string $identifier
     *
     * @return AppEvent
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return AppEvent
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
     * Set recordDate
     *
     * @param \DateTime $recordDate
     *
     * @return AppEvent
     */
    public function setRecordDate($recordDate)
    {
        $this->recordDate = $recordDate;

        return $this;
    }

    /**
     * Get recordDate
     *
     * @return \DateTime
     */
    public function getRecordDate()
    {
        return $this->recordDate;
    }

    /**
     * Set location
     *
     * @param point $location
     *
     * @return AppEvent
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return point
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set user
     *
     * @param \VT\ApiBundle\Entity\User $user
     *
     * @return AppEvent
     */
    public function setUser(\VT\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \VT\ApiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
