<?php

namespace VT\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class WelcomeController extends Controller
{

    public function angularAction()
    {
        $locale = 'fr';
        if (! empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY') || $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $user = $this->container->get('security.context')->getToken()->getUser();
            return $this->render('ApiBundle:Angular:angular.html.twig', array('user_role' => $user->getRole(), 'user_locale' => $locale));
        }
        else
        {
            return $this->render('ApiBundle:Angular:angular.html.twig', array('user_role' => "undefined", 'user_locale' => $locale));
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function checkoutAction()
    {
        $locale = 'fr';
        if (! empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
        return $this->render('ApiBundle:Checkout:checkout.html.twig', array('user_locale' => $locale));
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function profileAction()
    {
        return $this->render('ApiBundle:Account:profile.html.twig');
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function professorsAction()
    {
        return $this->render('ApiBundle:Account:professors.html.twig');
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        return $this->render('ApiBundle:welcome:index.html.twig');
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function welcomeAction()
    {
        return $this->render('ApiBundle:welcome:indexConnected.html.twig');
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function filingAction()
    {
      return $this->render('ApiBundle:Filing:documentFiling.html.twig');
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function supportAction()
    {
      return $this->render('ApiBundle:Support:support.html.twig');
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function forumAction()
    {
      return $this->render('ApiBundle:Forum:forum.html.twig');
    }
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function contributionSubjectAction()
    {
      return $this->render('ApiBundle:Forum:contribution.html.twig');
    }
}
