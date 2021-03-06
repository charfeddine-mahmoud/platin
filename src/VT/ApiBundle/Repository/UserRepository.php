<?php

namespace VT\ApiBundle\Repository;
use VT\ApiBundle\Entity\User;
use VT\ApiBundle\Exception\VTExceptionService;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{

	public function login($firstName, $lastName, $dateOfBirth, $email, $gender, $fbId, $location = array(), $version, $container, $em)
    {
        $result = VTExceptionService::STATUS_INTERNAL_ERROR; // by default

        $user = $em->getRepository('ApiBundle:User')->findOneBy(array("fbId" => $fbId));
        if (empty($user)) 
        {
        	$user = new User();
        	$user->setFirstName($firstName);
        	$user->setLastName($lastName);
        	$user->setEmail($email);
        	$user->setGender($gender);
        	$user->setBirthday(new \DateTime($dateOfBirth));
        	$user->setFbId($fbId);
        	$em->persist($user);
        	$em->flush();

        	return VTExceptionService::STATUS_OBJECT_CREATED;
        }
        else
        {
        	return VTExceptionService::STATUS_OK;
        }

        return $result;
    }
}
