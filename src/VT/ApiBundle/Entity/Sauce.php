<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sauce
 *
 * @ORM\Table(name="sauce")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\SauceRepository")
 */
class Sauce
{
    /**
     * @var integer
     *
     * @ORM\Column(name="sauceId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sauceName", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sauceActive", type="boolean", options={"unsigned"=true,"default" = 1}, nullable=false)
     */
    private $active;


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
     * Set name
     *
     * @param string $name
     *
     * @return Sauce
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Sauce
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
}
