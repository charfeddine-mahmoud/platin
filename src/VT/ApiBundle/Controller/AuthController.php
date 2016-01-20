<?php

namespace VT\ApiBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class AuthController extends Controller
{
    /**
     * allow to display login page
     *
     * @param  Request $request
     * @return array
     */
    public function loginAction(Request $request)
    {
        $locale = 'fr';
        if (! empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        }
        $helper = $this->get('security.authentication_utils');

        return $this->render('ApiBundle:Security:login.html.twig', array(
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
            'user_locale' => $locale
        ));
    }
}