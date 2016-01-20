<?php

namespace VT\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * NAME
 *
 */
interface VTExceptionInterface extends HttpExceptionInterface
{
    const STATUS_OK = "ok"; // 200
    const STATUS_OBJECT_CREATED = "objectCreated"; // 201
    const STATUS_MULTI_STATUS = "multiStatus"; // 207
    const STATUS_CONTENT_DIFFERENT = "contentDifferent"; // 210
    const STATUS_NOT_MODIFIED = "notModified"; // 304
    const STATUS_INVALID_PARAM = "invalidParam"; // 400
    const STATUS_MISSING_PARAM = "missingParam"; // 400
    const STATUS_FAILED_AUTHENTICATION = "failedAuthentication"; // 401
    const STATUS_UNAUTHORIZED_CLIENT = "unauthorizedClient"; //401
    const STATUS_VERSION_DEPRECATED = "versionDeprecated"; //401
    const STATUS_MISSING_PERMISSIONS = "missingPermissions"; //401
    const STATUS_EXPIRED_AUTHENTICATION = "expiredAuthentication"; //401
    const STATUS_OBJECT_UNAVAILABLE = "objectUnavailable"; // 401
    const STATUS_RESOURCE_FORBIDDEN = "resourceForbidden"; // 403
    const STATUS_SERVICE_NOT_FOUND = "serviceNotFound"; // 404
    const STATUS_OBJECT_NOT_FOUND = "objectNotFound"; // 404
    const STATUS_METHOD_NOT_ALLOWED = "methodNotAllowed"; // 405
    const STATUS_FORMAT_NOT_SUPPORTED = "formatNotSupported"; // 406
    const STATUS_CONTENT_NOT_SUPPORTED = "contentNotSupported"; //415
    const STATUS_INTERNAL_ERROR = "internalError"; // 500
    const STATUS_EXTERNAL_ERROR = "externalError"; // 500

    /**
     * Return service response code
     *
     * @return string
     */
    public function getResponseStatus();

    /**
     * Return array of service response data
     *
     * @return array
     */
    public function getServiceResponseData();
}