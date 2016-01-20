<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use VT\ApiBundle\Entity\User;

/**
 * ApiToken
 *
 * @ORM\Table(name="apitoken")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\ApiTokenRepository")
 */
class ApiToken
{
    /**
     * @var string
     *
     * @ORM\Column(name="apitokenId", type="string", length=128)
     * @ORM\Id
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="apitokenCreationDate", type="datetime")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="apitokenExpirationDate", type="datetime")
     */
    private $expirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="apitokenData", type="text", nullable=true)
     */
    private $data;

    /**
     * @var \VT\ApiBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\User", inversedBy="apiTokens")
     * @ORM\JoinColumn(name="apitoken_userId", referencedColumnName="userId")
     */
    private $user;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="\VT\ApiBundle\Entity\AppEvent", mappedBy="apitoken")
     * @ORM\JoinColumn(name="apitokenId", referencedColumnName="appevent_apitokenId")
     */
    private $appEvents;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="apitokenLastActivity", type="datetime", nullable=false)
     */
    private $lastActivity;


    /**
     * Construct
     *
     * @param string $id
     * @param \VT\ApiBundle\Entity\User $user
     */
    public function __construct($id, User $user)
    {
        $this->id = $id;
        $this->user = $user;
        $this->setCreationDate(new \DateTime());
        $this->setExpirationDate((new \DateTime())->modify('+50 day')); // token valid for 50 days
        $this->appEvents = new ArrayCollection();
        $this->setLastActivity(new \DateTime());
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationdate()
    {
        return $this->creationDate;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return ClientApiToken
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     * @return ApiToken
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return !is_null($this->expirationDate) && (new \DateTime())->getTimestamp() > $this->expirationDate->getTimestamp();
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data
     *
     * @param string $data
     * @return ApiToken
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * get Client
     *
     * @return Client
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param Client $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * get AppEvents
     *
     * @return AppEvents[]
     */
    public function getAppEvents()
    {
        return $this->appEvents;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     * @return ApiToken
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

}
