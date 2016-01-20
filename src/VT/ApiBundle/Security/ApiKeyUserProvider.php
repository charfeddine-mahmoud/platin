<?php

namespace VT\ApiBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use VT\ApiBundle\Repository\UserRepository;

class ApiKeyUserProvider implements UserProviderInterface
{

    protected $userRepository;


    /**
     * Here we inject the userRepository, to retriev easily our User from CommonBundle:User
     *
     * @param \ARD\CommonBundle\Repository\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     *
     *
     * @param type $apiKey
     * 
     * @return string, fbId of the user
     */
    public function getUsernameForApiKey($apiKey)
    {
        return $apiKey['fbId'] ?: null;
    }

    public function loadUserByUsername($username)
    {
        return $this->userRepository->findOneBy(array('fbId' => $username));
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}