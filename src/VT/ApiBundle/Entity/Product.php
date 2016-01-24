<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="productId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="productName", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="productSauce", type="boolean", options={"unsigned"=true,"default" = 1}, nullable=false)
     */
    private $sauce;

    /**
     * @var boolean
     *
     * @ORM\Column(name="productMeat", type="boolean", options={"unsigned"=true,"default" = 1}, nullable=false)
     */
    private $meat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="productAdditional", type="boolean", options={"unsigned"=true,"default" = 1}, nullable=false)
     */
    private $additional;

    /**
     * @var boolean
     *
     * @ORM\Column(name="productMenu", type="boolean", options={"unsigned"=true,"default" = 1}, nullable=false)
     */
    private $menu;

    /**
     * @var \VT\ApiBundle\Entity\Type
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\Type")
     * @ORM\JoinColumn(name="product_typeId", referencedColumnName="typeId")
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="productPrice", type="float", nullable=false)
     */
    private $price;

    /**
     * @var \VT\ApiBundle\Entity\Image
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\Image")
     * @ORM\JoinColumn(name="product_imageId", referencedColumnName="imageId")
     */
    private $image;


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
     * @return Product
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
     * Set sauce
     *
     * @param boolean $sauce
     *
     * @return Product
     */
    public function setSauce($sauce)
    {
        $this->sauce = $sauce;

        return $this;
    }

    /**
     * Get sauce
     *
     * @return boolean
     */
    public function getSauce()
    {
        return $this->sauce;
    }

    /**
     * Set meat
     *
     * @param boolean $meat
     *
     * @return Product
     */
    public function setMeat($meat)
    {
        $this->meat = $meat;

        return $this;
    }

    /**
     * Get meat
     *
     * @return boolean
     */
    public function getMeat()
    {
        return $this->meat;
    }

    /**
     * Set additional
     *
     * @param boolean $additional
     *
     * @return Product
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;

        return $this;
    }

    /**
     * Get additional
     *
     * @return boolean
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Product
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
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

    /**
     * Set menu
     *
     * @param boolean $menu
     *
     * @return Product
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return boolean
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set image
     *
     * @param \VT\ApiBundle\Entity\Image $image
     *
     * @return Product
     */
    public function setImage(\VT\ApiBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \VT\ApiBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
}
