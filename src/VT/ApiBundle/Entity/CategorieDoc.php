<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieDoc
 *
 * @ORM\Table(name="categorydoc")
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\CategorieDocRepository")
 */
class CategorieDoc
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \VT\ApiBundle\Entity\Categorie
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="categorieId", referencedColumnName="id")
     */
    private $categorieId;

    /**
     * @var \VT\ApiBundle\Entity\File
     * @ORM\ManyToOne(targetEntity="\VT\ApiBundle\Entity\File")
     * @ORM\JoinColumn(name="fileId", referencedColumnName="id")
     */
    private $fileId;


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
     * Set categorieId
     *
     * @param integer $categorieId
     * @return CategorieDoc
     */
    public function setCategorieId($categorieId)
    {
        $this->categorieId = $categorieId;

        return $this;
    }

    /**
     * Get categorieId
     *
     * @return integer 
     */
    public function getCategorieId()
    {
        return $this->categorieId;
    }

    /**
     * Set fileId
     *
     * @param integer $fileId
     * @return CategorieDoc
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;

        return $this;
    }

    /**
     * Get fileId
     *
     * @return integer 
     */
    public function getFileId()
    {
        return $this->fileId;
    }
}
