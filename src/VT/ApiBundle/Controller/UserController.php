<?php

namespace VT\ApiBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use VT\ApiBundle\Controller\RestController;
use VT\ApiBundle\Service\VTParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use VT\ApiBundle\Exception\VTExceptionService;
use FOS\RestBundle\Request\ParamFetcher;
use VT\ApiBundle\Entity\User;

class UserController extends RestController
{
    /**
     * Get user data
     * @return type
     */
    public function getUserAction()
    {
        $apiToken = $this->getApiToken();
        $user = $apiToken->getUser();
        $result = array(
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'score' => $user->getScore(),
            'gender' => $user->getGender(),
            'fbId' => $user->getFbId(),
            'birthday' => empty($user->getBirthday()) ? null : $user->getBirthday()->format('m/d/Y')
        );

        // Return API response
        return $this->view($result);
    }
    
}