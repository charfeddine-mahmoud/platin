<?php

namespace VT\ApiBundle\Controller;

use VT\ApiBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;


class UserController extends RestController
{
    /**
     * [userAction Return the data about one specific user]
     * @param  Request $request
     * 
     * @return Array
     */
    public function userAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') || $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) 
        {
            $user = $this->get('security.context')->getToken()->getUser();
            $name = $user->getFirstName();
            $surname = $user->getLastName();
            $email = $user->getEmail();
            $phone = $user->getPhone();
            $password = "mot de passe";
            $passwordConfirm = "confirmation mot de passe";
            $data = array("name" => $name, "surname" => $surname, "email" => $email, "phone" => $phone, "password" => $password, "passwordConfirm" => $passwordConfirm);
        }

        return $data;
    }

    /**
     * [updateUserDataAction update user data]
     * @param  Request $request
     * 
     * @return 
     */
    public function updateUserDataAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $user = $this->get('security.context')->getToken()->getUser();
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $result = '';
        switch ($data["type"]) {
            case 'name':
                $user->setFirstName($data["value"]);
                $em->persist($user);
                $result = "Le nom a été mis à jour";
                break;
            case 'surname':
                $user->setLastName($data["value"]);
                $em->persist($user);
                $result = "Le prénom a été mis à jour";
                break;
            case 'email':
                $user->setEmail($data["value"]);
                $em->persist($user);
                $result = "L'email a été mis à jour";
                break;
            case 'phone':
                $user->setPhone($data["value"]);
                $em->persist($user);
                $result = "Le téléphone a été mis à jour";
                break;
            case 'password':
                // to change salt
                $salt = "cjezptqt56a20c64dc9ca";
                $password = $encoder->encodePassword($data["value"], $salt);
                $user->setPassword($password);
                $user->setSalt($salt);
                $em->persist($user);
                $result = "Le mot de passe a été mis à jour";
                break;            
        }
        $em->flush();

        return $result;
    }

    public function getProfessorsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $data = [];
        $professors = $em->getRepository('ApiBundle:User')->findBy(array("role" => "ENSEIGNANT"));
        foreach ($professors as $professor) {
            $data[] = array("userId" => $professor->getId(), "name" => $professor->getFirstName(), "lastname" => $professor->getLastName(), "email" => $professor->getEmail());
        }

        return $data;
    }

    public function addUserDataAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $factory = $this->get('security.encoder_factory');
        if(!empty($data))
        {
            $salt = "cjezptqt56a20c64dc9ca";
            $user = new User();
            $user->setActive(1);
            $user->setEmail($data["email"]);
            $user->setFirstName($data["name"]);
            $user->setLastName($data["lastname"]);
            $user->setPhone($data["phone"]);
            $user->setRole($data["role"]);
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($data["password"], $salt);
            $user->setPassword($password);
            $user->setSalt($salt);
            $em->persist($user);
            $em->flush();

            return "L'utilisateur a été ajouté avec success";
        } 

        return "l'utilisateur n'a pas pu être ajouter";
    }

    public function getUserDataAction(User $user)
    {
        $data = [];
        $name = $user->getFirstName();
        $lastname = $user->getLastName();
        $email = $user->getEmail();
        $phone = $user->getPhone();
        $password = "";
        $passwordConfirmation = "";
        $data = array("userId" => $user->getId(), "name" => $name, "lastname" => $lastname, "email" => $email, "phone" => $phone, "password" => $password, "passwordConfirmation" => $passwordConfirmation);

        return $data;
    }

    public function updateUserAction(Request $request)
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $factory = $this->get('security.encoder_factory');
        if(!empty($data))
        {
            $user = $em->getRepository('ApiBundle:User')->find($data["userId"]);
            if ($data["password"] != 0) {
                $salt = "cjezptqt56a20c64dc9ca";
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($data["password"], $salt);
                $user->setPassword($password);
                $user->setSalt($salt);
            }
            $user->setEmail($data["email"]);
            $user->setFirstName($data["name"]);
            $user->setLastName($data["lastname"]);
            $user->setPhone($data["phone"]);
            $em->persist($user);
            $em->flush();

            return "L'utilisateur a été mis à jour avec success";
        } 

        return "l'utilisateur n'a pas pu être mis à jour";
    }
}
