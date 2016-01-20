<?php

namespace VT\ApiBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use FOS\RestBundle\Controller\FOSRestController;
use VT\ApiBundle\Exception\VTExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * RestController
 *
 * Global RestController
 * We force $data to be an array and we add default success status code if necessary
 */
abstract class RestController extends FOSRestController {
     
    /**
     * Format the view for our API, and return a parent View
     *
     * @param mixed $response Response
     * @param VTExceptionInterface|int|null|string $status
     * @param array $headers
     * @param boolean $processActivity do we have to set the lastActivity for the current APIToken ?
     * 
     * @return FOS\RestBundle\View\View
     */
    protected function view($response = null, $status = VTExceptionInterface::STATUS_OK, array $headers = array(), $processActivity = true) {
        if (is_array($response)) {
        }
        if (empty($response)) {
            $response = null;
        }

        $data = array(
            'response' => $response,
            'serverTimestamp' => (new \DateTime())->getTimestamp(),
            'status' => $status
        );

        return parent::view($data, $this->getStatusCode($status), $headers, $processActivity);
    }

    /**
     * Return the current apiTokenEntity
     *
     * @return \VT\ApiBundle\Entity\ApiToken
     */
    protected function getApiToken() {
        return $this->getToken()->getApiToken();
    }

    /**
     * Return the current token from the security context.
     * ! This is not the apiTokenEntity ;)
     *
     * @return mixed
     */
    protected function getToken() {
        return $this->container->get('security.context')->getToken();
    }

    /**
     * Get the statusCode (int) from the VTExceptionInterface $status
     *
     * @param VTExceptionInterface $status
     * 
     * @return int, HTTP status code
     */
    private function getStatusCode($status) {
        switch ($status) {
            case VTExceptionInterface::STATUS_OK:
                return Response::HTTP_OK;
            case VTExceptionInterface::STATUS_OBJECT_CREATED:
                return Response::HTTP_CREATED;
            case VTExceptionInterface::STATUS_MULTI_STATUS:
                return Response::HTTP_MULTI_STATUS;
            case VTExceptionInterface::STATUS_CONTENT_DIFFERENT:
                return 210; // Not in symfony
            case VTExceptionInterface::STATUS_NOT_MODIFIED:
                return Response::HTTP_NOT_MODIFIED;
            case VTExceptionInterface::STATUS_INVALID_PARAM:
            case VTExceptionInterface::STATUS_MISSING_PARAM:
                return Response::HTTP_BAD_REQUEST;
            case VTExceptionInterface::STATUS_FAILED_AUTHENTICATION:
            case VTExceptionInterface::STATUS_UNAUTHORIZED_CLIENT:
            case VTExceptionInterface::STATUS_EXPIRED_AUTHENTICATION:
            case VTExceptionInterface::STATUS_OBJECT_UNAVAILABLE:
                return Response::HTTP_UNAUTHORIZED;
            case VTExceptionInterface::STATUS_RESOURCE_FORBIDDEN:
                return Response::HTTP_FORBIDDEN;
            case VTExceptionInterface::STATUS_SERVICE_NOT_FOUND:
            case VTExceptionInterface::STATUS_OBJECT_NOT_FOUND:
                return Response::HTTP_NOT_FOUND;
            case VTExceptionInterface::STATUS_METHOD_NOT_ALLOWED:
                return Response::HTTP_METHOD_NOT_ALLOWED;
            case VTExceptionInterface::STATUS_FORMAT_NOT_SUPPORTED:
                return Response::HTTP_NOT_ACCEPTABLE;
            case VTExceptionInterface::STATUS_CONTENT_NOT_SUPPORTED:
                return Response::HTTP_UNSUPPORTED_MEDIA_TYPE;
            case VTExceptionInterface::STATUS_INTERNAL_ERROR:
            case VTExceptionInterface::STATUS_EXTERNAL_ERROR:
                return Response::HTTP_INTERNAL_SERVER_ERROR;

            default:
                return Response::HTTP_OK;
        }
    }

    /**
     * Return the locale for the current user
     *
     * @return string locale of the current logged in user
     */
    protected function getLocale() {
        $dataTokenUnserialized = unserialize($this->getApiToken()->getData());

        return isset($dataTokenUnserialized['locale']) ? $dataTokenUnserialized['locale'] : 'en_US';
    }

}
