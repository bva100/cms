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
        echo '<pre>', \var_dump($_SERVER); die();
        return $this->render('CmsCoreBundle:Welcome:index.html.twig');
    }

    public function signupAction()
    {
        return $this->render('CmsCoreBundle:Welcome:signup.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('CmsCoreBundle:Welcome:about.html.twig');
    }

    public function aboutSlidesAction()
    {
        return $this->render('CmsCoreBundle:Welcome:aboutSlides.html.twig');
    }

}