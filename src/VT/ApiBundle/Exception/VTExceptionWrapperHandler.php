<?php

namespace VT\ApiBundle\Exception;

use FOS\RestBundle\View\ExceptionWrapperHandlerInterface;
use VT\ApiBundle\Exception\VTExceptionWrapper;

/**
 * Description of ExceptionWrapperHandler
 *
 */
class VTExceptionWrapperHandler implements ExceptionWrapperHandlerInterface
{

    /**
     * {@inheritdoc}
     */
    public function wrap($data)
    {
        return new VTExceptionWrapper($data);
    }

}
