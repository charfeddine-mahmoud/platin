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
}
