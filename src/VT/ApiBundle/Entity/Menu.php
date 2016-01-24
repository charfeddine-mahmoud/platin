<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\MenuRepository")
 */
class Menu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="menuId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \VT\ApiBundle\Entity\Product
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\Product")
     * @ORM\JoinColumn(name="menu_productId", referencedColumnName="productId")
     */
    private $product;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menuActive", type="boolean", options={"unsigned"=true,"default" = 1}, nullable=false)
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
     * Set active
     *
     * @param boolean $active
     *
     * @return Menu
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
     * Set product
     *
     * @param \VT\ApiBundle\Entity\Product $product
     *
     * @return Menu
     */
    public function setProduct(\VT\ApiBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \VT\ApiBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
