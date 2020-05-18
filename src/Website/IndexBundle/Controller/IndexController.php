<?php

namespace Website\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;

class IndexController extends Controller
{

    public function indexAction(Request $request)
    {

        return $this->render('WebsiteIndexBundle:Index:index.html.twig', array(
            'page_title' => 'Main Page',
        ));
    }

}
