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
     * @ORM\JoinColumn(name="menu_entryId", referencedColumnName="productId")
     */
    private $entry;

    /**
     * @var \VT\ApiBundle\Entity\Product
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\Product")
     * @ORM\JoinColumn(name="menu_mainCourseId", referencedColumnName="productId")
     */
    private $mainCourse;

    /**
     * @var \VT\ApiBundle\Entity\Product
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\Product")
     * @ORM\JoinColumn(name="menu_dessertId", referencedColumnName="productId")
     */
    private $dessert;


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
     * Set entry
     *
     * @param integer $entry
     *
     * @return Menu
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return integer
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Set mainCourse
     *
     * @param integer $mainCourse
     *
     * @return Menu
     */
    public function setMainCourse($mainCourse)
    {
        $this->mainCourse = $mainCourse;

        return $this;
    }

    /**
     * Get mainCourse
     *
     * @return integer
     */
    public function getMainCourse()
    {
        return $this->mainCourse;
    }

    /**
     * Set dessert
     *
     * @param integer $dessert
     *
     * @return Menu
     */
    public function setDessert($dessert)
    {
        $this->dessert = $dessert;

        return $this;
    }

    /**
     * Get dessert
     *
     * @return integer
     */
    public function getDessert()
    {
        return $this->dessert;
    }
}

