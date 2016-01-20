<?php

namespace VT\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use VT\ApiBundle\Entity\ApiToken;
use VT\ApiBundle\Entity\User;

/**
 * ApiTokenRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApiTokenRepository extends EntityRepository
{

    /**
     * Set a data in the apiTokenEntity Data
     *
     * @param ApiToken $apiToken
     * @param string $key
     * @param mixed $value
     */
    public function setApiTokenSingleData(ApiToken $apiToken, $key, $value)
    {
        $apiTokenData = $this->getApiTokenData($apiToken);
        $apiTokenData[$key] = $value;
        $apiToken->setData(serialize($apiTokenData));
        $this->getEntityManager()->persist($apiToken);
        $this->getEntityManager()->flush($apiToken);
    }

    /**
     * Get the unserialized data from a given apiTokenEntity
     *
     * @param ApiToken $apiToken
     * 
     * @return array
     */
    private function getApiTokenData(ApiToken $apiToken = null)
    {
        if (is_null($apiToken)) {
            return array();
        }

        return empty(unserialize($apiToken->getData())) ? array() : unserialize($apiToken->getData());
    }

    /**
     * Get the locale of a given apiTokenEntity
     *
     * @param ApiToken $apiToken
     * 
     * @return string | null
     */
    public function getApiTokenLocale(ApiToken $apiToken = null)
    {
        $apiTokenData = $this->getApiTokenData($apiToken);
        if (isset($apiTokenData['locale'])) {
            return $apiTokenData['locale'];
        }

        return null;
    }

    /**
     * Get the country code of a given apiTokenEntity
     *
     * @param ApiToken $apiToken
     * 
     * @return string | null
     */
    public function getApiTokenCountryCode(ApiToken $apiToken = null)
    {
        $apiTokenData = $this->getApiTokenData($apiToken);
        if (isset($apiTokenData['countryCode'])) {
            return $apiTokenData['countryCode'];
        }

        return null;
    }

    /**
     * Get the version of a given apiTokenEntity
     *
     * @param ApiToken $apiToken
     * 
     * @return string | null
     */
    public function getApiTokenVersion(ApiToken $apiToken = null)
    {
        $apiTokenData = $this->getApiTokenData($apiToken);
        if (isset($apiTokenData['version'])) {
            return $apiTokenData['version'];
        }

        return null;
    }


    /**
     * Get the lastStatePush of a given apiTokenEntity
     *
     * @param ApiToken $apiToken
     * 
     * @return string | null
     */
    public function getLastStatePush(ApiToken $apiToken = null)
    {
        $apiTokenData = $this->getApiTokenData($apiToken);
        if (isset($apiTokenData['lastStatePush'])) {
            return $apiTokenData['lastStatePush'];
        }

        return null;
    }

    /**
     * Get the needsCurrentState of a given apiTokenEntity
     * needsCurrentState is a boolean. If true, the API needs to know if the mobile currently see some beacons, or so on, after it lost Internet Connexion for ex.
     *
     * @param ApiToken $apiToken
     * 
     * @return boolean
     */
    public function getApiTokenNeedsCurrentState(ApiToken $apiToken = null)
    {
        $apiTokenData = $this->getApiTokenData($apiToken);
        if (isset($apiTokenData['needsCurrentState'])) {
            return $apiTokenData['needsCurrentState'];
        }

        return false;
    }

    /**
     * Return the current location of the user based on a given apiTokenEntity
     *
     * @param ApiToken $apiToken
     * 
     * @return array
     */
    public function getApiTokenLocation(ApiToken $apiToken = null)
    {
        $result = array(
            'x' => null,
            'y' => null
        );

        $apiTokenData = $this->getApiTokenData($apiToken);
        if (isset($apiTokenData['location']) && isset($apiTokenData['location']['x']) && isset($apiTokenData['location']['y'])) {
            $result['x'] = $apiTokenData['location']['x'];
            $result['y'] = $apiTokenData['location']['y'];
        }

        return $result;
    }

    /**
     * Get the last ApiToken for a given user
     * 
     * @param User $user
     * 
     * @return ApiToken
     */
    public function getLastApiTokenForUser(User $user){
        return $this->findOneBy(array('user' => $user), array('expirationDate' => 'DESC'));
    }
}