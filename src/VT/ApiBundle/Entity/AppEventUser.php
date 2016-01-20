<?php

namespace VT\ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppEventUser
 *
 * @ORM\Entity(repositoryClass="VT\ApiBundle\Repository\AppEventUserRepository")
 * @ORM\Table(name="appeventuser")
 */
class AppEventUser extends AppEvent
{

    /**
     * @var string
     *
     * @ORM\Column(name="appeventuserSubType", type="string", columnDefinition="enum('SIGNED','SEEN')", nullable=false)
     */
    private $subType;

    /**
     * @var string
     *
     * @ORM\Column(name="appeventuserSignedType", type="string", columnDefinition="enum('IN','OUT')")
     */
    private $signedType;

    /**
     * {@inheritdoc}
     */
    public static function getAvailableSubTypes()
    {
        return array(
            'SIGNED',
            'SEEN'
        );
    }

    /**
     * @return string
     */
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * @param string $subType
     */
    public function setSubType($subType)
    {
        $this->subType = $subType;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignedType()
    {
        return $this->signedType;
    }

    /**
     * @param string $signedType
     */
    public function setSignedType($signedType)
    {
        $this->signedType = $signedType;
        return $this;
    }

}
