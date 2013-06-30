<?php
/**
 * User: Brian Anderson
 * Date: 6/27/13
 * Time: 4:36 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class WelcomeController extends Controller {

    public function indexAction()
    {
        // check for cookie to login
        return $this->render('CmsCoreBundle:Welcome:index.html.twig');
    }

    public function signupAction()
    {
        // check for cookie to login
        return $this->render('CmsCoreBundle:Welcome:signup.html.twig');
    }

}