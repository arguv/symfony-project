<?php

namespace Website\UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\UserBundle\Controller\SecurityController as SecurityController;

class LoginController extends SecurityController
{

    public function loginAction(\Symfony\Component\HttpFoundation\Request $request){

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->generateUrl('fos_user_profile_show'));
        }

        return parent::loginAction($request);
    }

    public function renderLogin(array $data)
    {
        /*if ($this->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->generateUrl('profile'));
        }*/

        return $this->render('@FOSUser/Security/login.html.twig', $data);
    }

}
