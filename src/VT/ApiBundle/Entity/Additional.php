<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Additional
 *
 * @ORM\Table(name="addtional")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\AdditionalRepository")
 */
class Additional
{
    /**
     * @var integer
     *
     * @ORM\Column(name="additionalId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="additionalName", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="additionalPayant", type="boolean", options={"unsigned"=true,"default" = 1}, nullable=false)
     */
    private $payant;

    /**
     * @var float
     *
     * @ORM\Column(name="additionalPrice", type="float", nullable=true)
     */
    private $price;


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
     * @return Additional
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
     * Set payant
     *
     * @param boolean $payant
     *
     * @return Additional
     */
    public function setPayant($payant)
    {
        $this->payant = $payant;

        return $this;
    }

    /**
     * Get payant
     *
     * @return boolean
     */
    public function getPayant()
    {
        return $this->payant;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Additional
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }
}

