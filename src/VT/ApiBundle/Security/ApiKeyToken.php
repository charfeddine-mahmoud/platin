<?php

namespace VT\ApiBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use VT\ApiBundle\Entity\User;
use VT\ApiBundle\Entity\ApiToken;
use Doctrine\ORM\EntityManager;

class ApiKeyToken extends PreAuthenticatedToken
{

    protected $em;
    protected $apiTokenRepository;
    protected $locale;
    protected $version;
    protected $countryCode;

    /**
     *
     * @param User $user
     * @param type $credentials
     * @param type $providerKey
     * @param Array $roles
     * @param string $apiTokenEntityId
     * @param EntityManager $em
     * @param String $locale
     * @param String $version
     * @param String $countryCode
     */
    public function __construct($user, $credentials, $providerKey, array $roles = array(), $apiTokenEntityId = null, EntityManager $em, $locale, $version, $countryCode)
    {
        parent::__construct($user, $credentials, $providerKey, $roles);
        $this->em = $em;
        $this->apiTokenRepository = $this->em->getRepository('ApiBundle:ApiToken');
        $this->locale = $locale;
        $this->version = $version;
        $this->countryCode = $countryCode;

        // New tokenEntity generation. So, we will insert it in the DB, and remove access to the precedents
        if (!empty($this->getUser()) && $this->getUser() instanceof User && empty($apiTokenEntityId)) {
            $apiTokenEntityId = base64_encode(openssl_random_pseudo_bytes(92));

            // get Data object of the last apiToken, to copy past some of it to the new one
            $lastpiTokenEntity = $this->apiTokenRepository->findOneBy(array('user' => $this->getUser()), array('expirationDate' => 'DESC'));

            // Remove validity on precedent apitokens entities for this user
            //     by security we try to get a collections, even if the logic would be just to get one (lastApiTokenEntity)
            $query_apiTokensToInvalidate = $this->apiTokenRepository->createQueryBuilder('a')
                ->where('a.expirationDate > :now')
                ->andWhere('a.user = :u')
                ->setParameter('now', new \DateTime())
                ->setParameter('u', $this->getUser())
                ->getQuery();

            $apiTokensToInvalidate = $query_apiTokensToInvalidate->getResult();

            foreach ($apiTokensToInvalidate as $apiToken) {
                // change expiration date to now
                $apiToken->setExpirationDate(new \DateTime());
                $this->em->persist($apiToken);
            }

            $newApiTokenData = array();
            $newApiTokenData['locale'] = is_null($locale) ? $this->apiTokenRepository->getApiTokenLocale($lastApiTokenEntity) : $locale;
            $newApiTokenData['countryCode'] = is_null($countryCode) ? $this->apiTokenRepository->getApiTokenCountryCode($lastApiTokenEntity) : $countryCode;
            $newApiTokenData['needsCurrentState'] = $this->apiTokenRepository->getApiTokenNeedsCurrentState($lastApiTokenEntity);
            $newApiTokenData['location'] = $this->apiTokenRepository->getApiTokenLocation($lastApiTokenEntity);
            $newApiTokenData['version'] = $version;

            // generate new apiTokenEntity
            $apiTokenEntity = new ApiToken($apiTokenEntityId, $this->getUser());

            foreach ($newApiTokenData as $k => $v) {
                $this->apiTokenRepository->setApiTokenSingleData($apiTokenEntity, $k, $v);
            }

            $this->em->persist($apiTokenEntity);
            $this->em->flush();
        }
    }

    /** {@inheritdoc} */
    public function getApiToken()
    {
        $apiToken = $this->apiTokenRepository->findOneBy(array('user' => $this->getUser()), array('creationDate' => 'DESC'));
        return $apiToken;
    }

    /**
     * Return the locale for the user
     *
     * @return String
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Return the version of the mobile application currently used by the user
     *
     * @return String
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Return the countryCode for the user
     *
     * @return String
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

}