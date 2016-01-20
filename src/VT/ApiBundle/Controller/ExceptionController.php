<?php

/**
 * ARD custom ExceptionController
 */

namespace VT\ApiBundle\Controller;

use FOS\RestBundle\Controller\ExceptionController as FOSExceptionController;

use FOS\RestBundle\View\ViewHandler;
use VT\ApiBundle\Exception\VTExceptionInterface;
use VT\ApiBundle\Exception\VTExceptionService;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use ReflectionClass;

/**
 * ExceptionController
 *
 * Custom Exception Controller for VT
 */
class ExceptionController extends FOSExceptionController
{

    protected function getParameters(ViewHandler $viewHandler, $currentContent, $code, $exception, DebugLoggerInterface $logger = null, $format = 'html')
    {
        $result = parent::getParameters($viewHandler, $currentContent, $code, $exception, $logger, $format);

        // We are going to check for each Constants defined in VTExceptionInterface, if our $exception (Flatten) is coming from an VTExceptionInterface.
        $refl = new ReflectionClass('VT\ApiBundle\Exception\VTExceptionInterface');
        foreach ($refl->getConstants() as $errorType) {
            if ($exception->getMessage() == $errorType) {
                $exception = new VTExceptionService($errorType);
            }
        }

        // Then, if it wasn't from ARDExceptionService, we use the following fallback
        // This is not extremely precise, but that's ok
        if ($exception instanceof FlattenException) {
            switch ($exception->getStatusCode()) {

                case 400:
                    $exception = new VTExceptionService(VTExceptionInterface::STATUS_INVALID_PARAM);
                    break;
                case 401:
                    $exception = new VTExceptionService(VTExceptionInterface::STATUS_UNAUTHORIZED_CLIENT);
                    break;
                case 403:
                    $exception = new VTExceptionService(VTExceptionInterface::STATUS_RESOURCE_FORBIDDEN);
                    break;
                case 404:
                    $exception = new VTExceptionService(VTExceptionInterface::STATUS_SERVICE_NOT_FOUND);
                    break;
                case 405:
                    $exception = new VTExceptionService(VTExceptionInterface::STATUS_METHOD_NOT_ALLOWED);
                    break;
                case 406:
                    $exception = new VTExceptionService(VTExceptionInterface::STATUS_FORMAT_NOT_SUPPORTED);
                    break;
                case 415:
                    $exception = new VTExceptionService(VTExceptionInterface::STATUS_CONTENT_NOT_SUPPORTED);
                    break;
            }
        }

        if (!$exception instanceof VTExceptionInterface) {
            $result['vt_status'] = VTExceptionInterface::STATUS_INTERNAL_ERROR;
            $result['vt_data'] = array();
        }
        else
        {
            $result['vt_status'] = $exception->getResponseStatus();
            $result['vt_data'] = $exception->getServiceResponseData();
        }

        return $result;
    }


}