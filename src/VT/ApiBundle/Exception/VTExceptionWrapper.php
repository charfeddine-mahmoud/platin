<?php

namespace VT\ApiBundle\Exception;

use VT\ApiBundle\Exception\VTExceptionService;
use JMS\Serializer\Annotation as JMS;

/**
 * NAME
 *
 *
 * @JMS\XmlRoot("response")
 */
class VTExceptionWrapper
{
    /**
     * @var string
     *
     * @JMS\SerializedName("status")
     */
    private $status;

    /**
     * @var string
     *
     * @JMS\SerializedName("serverTimestamp")
     */
    private $serverTimestamp;

    public function __construct($data)
    {
        $this->status = isset($data['vt_status']) ? $data['vt_status'] : VTExceptionService::STATUS_INTERNAL_ERROR;
        $this->serverTimestamp = time();
    }
}