<?php

namespace VT\ApiBundle\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * ResponseExceptionService
 *
 * Manage the response exception for the API
 */
class VTExceptionService extends \Exception implements VTExceptionInterface
{
    private $responseCode;
    private $responseData;
    private $httpHeaders;
    private $previousException;

    public function __construct($respCode, array $respData = null, array $httpHeaders = array(), $previous = null)
    {
        parent::__construct($respCode, 0);
        $this->previousException = $previous;
        $this->responseCode = $respCode;
        $this->responseData = is_array($respData) ? $respData : array();
        $this->httpHeaders = $httpHeaders;
    }

    /** {@inheritdoc} */
    public function getServiceResponseData()
    {
        return array_merge(
            $this->responseData,
            array(
                "status" => $this->getResponseStatus(),
            )
        );
    }

    /** {@inheritdoc} */
    public function getResponseStatus()
    {
        return $this->responseCode;
    }

    /** {@inheritdoc} */
    public function getStatusCode()
    {
        $mapper = array(
            self::STATUS_OK => Response::HTTP_OK,
            self::STATUS_OBJECT_CREATED => Response::HTTP_CREATED,
            self::STATUS_MULTI_STATUS => Response::HTTP_MULTI_STATUS,
            self::STATUS_CONTENT_DIFFERENT => 210, // Not in symfony
            self::STATUS_NOT_MODIFIED => Response::HTTP_NOT_MODIFIED,
            self::STATUS_INVALID_PARAM => Response::HTTP_BAD_REQUEST,
            self::STATUS_MISSING_PARAM => Response::HTTP_BAD_REQUEST,
            self::STATUS_FAILED_AUTHENTICATION => Response::HTTP_UNAUTHORIZED,
            self::STATUS_UNAUTHORIZED_CLIENT => Response::HTTP_UNAUTHORIZED,
            self::STATUS_EXPIRED_AUTHENTICATION => Response::HTTP_UNAUTHORIZED,
            self::STATUS_OBJECT_UNAVAILABLE => Response::HTTP_UNAUTHORIZED,
            self::STATUS_RESOURCE_FORBIDDEN => Response::HTTP_FORBIDDEN,
            self::STATUS_SERVICE_NOT_FOUND => Response::HTTP_NOT_FOUND,
            self::STATUS_OBJECT_NOT_FOUND => Response::HTTP_NOT_FOUND,
            self::STATUS_METHOD_NOT_ALLOWED => Response::HTTP_METHOD_NOT_ALLOWED,
            self::STATUS_FORMAT_NOT_SUPPORTED => Response::HTTP_NOT_ACCEPTABLE,
            self::STATUS_CONTENT_NOT_SUPPORTED => Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
            self::STATUS_INTERNAL_ERROR => Response::HTTP_INTERNAL_SERVER_ERROR,
            self::STATUS_EXTERNAL_ERROR => Response::HTTP_INTERNAL_SERVER_ERROR,
            self::STATUS_VERSION_DEPRECATED => Response::HTTP_UNAUTHORIZED,
            self::STATUS_MISSING_PERMISSIONS => Response::HTTP_UNAUTHORIZED
        );

        return isset($mapper[$this->responseCode]) ? $mapper[$this->responseCode] : 400;
    }

    /** {@inheritdoc} */
    public function getHeaders()
    {
        return $this->httpHeaders;
    }
}