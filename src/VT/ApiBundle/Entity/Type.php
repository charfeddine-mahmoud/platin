<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table(name="type")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\TypeRepository")
 */
class Type
{
    /**
     * @var integer
     *
     * @ORM\Column(name="typeId", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="typeName", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var \VT\ApiBundle\Entity\Image
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\Image")
     * @ORM\JoinColumn(name="type_imageId", referencedColumnName="imageId")
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
     * @return Type
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
     * Set image
     *
     * @param \VT\ApiBundle\Entity\Image $image
     *
     * @return Type
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
