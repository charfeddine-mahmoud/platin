<?php

namespace VT\ApiBundle\Security;

use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use VT\ApiBundle\Security\ApiKeyToken;
use VT\ApiBundle\Exception\VTExceptionService;
use VT\ApiBundle\Repository\UserRepository;
use VT\ApiBundle\Entity\User;
use VT\ApiBundle\Repository\ApiTokenRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;


// use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    protected $userProvider;
    protected $userRepository;
    protected $apiTokenRepository;
    protected $em;
    protected $session;
    protected $apiTokenEntityId;
    protected $version;
    protected $container;

    public function __construct(ApiKeyUserProvider $userProvider, UserRepository $userRepository, ApiTokenRepository $apiTokenRepository, EntityManager $em, Session $session, $container)
    {
        $this->em = $em;
        $this->userProvider = $userProvider;
        $this->userRepository = $userRepository;
        $this->apiTokenRepository = $apiTokenRepository;
        $this->session = $session;
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param $providerKey
     * 
     * @return ApiKeyToken
     * @throws VTExceptionService
     */
    public function createToken(Request $request, $providerKey)
    {
        // look for app version in the header
        $this->version = $request->headers->has("X-App-Version") ? $request->headers->get('X-App-Version') : NULL;
        // look for an ApiTokenEntity id in the header
        if ($request->headers->has("X-Auth-Token")) {
            $this->apiTokenEntityId = $request->headers->get('X-Auth-Token');
            $apiTokenEntity = $this->apiTokenRepository->find($this->apiTokenEntityId);
            if ($apiTokenEntity && !$apiTokenEntity->isExpired()) {
                $user = $apiTokenEntity->getUser();
                $email = $user ? $user->getEmail() : null;
                $fbId = $user ? $user->getFbId() : null;
                if ($user->getActive() && $email && $fbId) {
                    // say login is ok (just for auth controller rare case)
                    if (($request->get('_route') === 'vt_api_auth')) {
                        $this->session->getFlashBag()->set('loginResult', VTExceptionService::STATUS_OK);
                    }
                    $dataTokenUnserialized = unserialize($apiTokenEntity->getData());
                    $this->apiTokenRepository->setApiTokenSingleData($apiTokenEntity,"version",$this->version);
                    $localLanguage = isset($dataTokenUnserialized['locale']) ? $dataTokenUnserialized['locale'] : 'en_US';
                    $countryCode = isset($dataTokenUnserialized['countryCode']) ? $dataTokenUnserialized['countryCode'] : 'FR';
                    return $this->getAuthenticatedToken($user, array("fbId" => $fbId), $providerKey, $localLanguage, $this->version, $countryCode);
                } elseif ($fbTokenIsExpired) {
                    throw new VTExceptionService(VTExceptionService::STATUS_EXPIRED_AUTHENTICATION);
                } else {
                    $this->apiTokenEntityId = null;
                }
            } else {
                $this->apiTokenEntityId = null;
            }
        }

        // If request comes from /Auth, we try to authenticate. Else, we return an authentication error.
        if (!($request->get('_route') === 'vt_api_auth')) {
            throw new VTExceptionService(VTExceptionService::STATUS_FAILED_AUTHENTICATION);
        }

        // If not X-Auth-Token, or if X-Auth-Token is expired or so on, we will try generating a new one
        if (!$request->request->has('firstName') || !$request->request->has('lastName') || !$request->request->has('dateOfBirth') || !$request->request->has('email') || !$request->request->has('gender') || !$request->request->has('fbId')) {
            throw new VTExceptionService(VTExceptionService::STATUS_MISSING_PARAM);
        }

        $login = $this->userRepository->login($request->get('firstName'), $request->get('lastName'), $request->get('dateOfBirth'), $request->get('email'), $request->get('gender'),  $request->get('fbId'), $request->get('location'), $this->version, $this->container, $this->em);

        // We will use it in AuthController
        $this->session->getFlashBag()->set('loginResult', $login);
        // Manage login errors
        switch ($login) {
            case VTExceptionService::STATUS_INTERNAL_ERROR:
                throw new VTExceptionService(VTExceptionService::STATUS_INTERNAL_ERROR);
        }
        return new ApiKeyToken(
            'anon.',
            array(
                "fbId" => $request->request->get('fbId')
            ),
            $providerKey,
            array(),
            null,
            $this->em,
            $request->get('locale') != "" ? $request->get('locale') : "en_US",
            $this->version,
            $request->get('countryCode') != "" ? $request->get('countryCode') : "FR"
        );
    }

    /**
     * Return AuthenticatedToken
     *
     * @param \ARD\CommonBundle\Entity\User $user
     * @param type $credentials
     * @param type $providerKey
     * 
     * @return \ARD\ApiBundle\Security\ApiKeyToken
     */
    private function getAuthenticatedToken(User $user, $credentials, $providerKey, $locale, $version, $countryCode)
    {
        return new ApiKeyToken(
            $user,
            $credentials,
            $providerKey,
            $user->getRoles(),
            $this->apiTokenEntityId,
            $this->em,
            $locale,
            $version,
            $countryCode
        );
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $credentials = $token->getCredentials();
        $locale = $token->getLocale();
        $version = $token->getVersion();
        $countryCode = $token->getCountryCode();

        $username = $this->userProvider->getUsernameForApiKey($credentials);

        if (!$username) {
            throw new VTExceptionService(VTExceptionService::STATUS_FAILED_AUTHENTICATION);
        }

        $user = $this->userProvider->loadUserByUsername($username);

        return $this->getAuthenticatedToken($user, $credentials, $providerKey, $locale, $version, $countryCode);
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof ApiKeyToken && $token->getProviderKey() === $providerKey;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new VTExceptionService(VTExceptionService::STATUS_FAILED_AUTHENTICATION);
    }
}