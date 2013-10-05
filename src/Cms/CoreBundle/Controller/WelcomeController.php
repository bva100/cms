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
        $isClient = $this->get('check_route')->setRequest($this->getRequest())->isClient();
        if ( $isClient )
        {
            return $this->forward('CmsCoreBundle:Client:nodeRead');
        }
        return $this->render('CmsCoreBundle:Welcome:index.html.twig');
    }

    public function cloudBaasAction()
    {
        return $this->render('CmsCoreBundle:Welcome:cloudBaas.html.twig');
    }

    public function backendCmsAction()
    {
        return $this->render('CmsCoreBundle:Welcome:backendCms.html.twig');
    }

    public function fasterLoadTimeAction()
    {
        return $this->render('CmsCoreBundle:Welcome:fasterLoadTime.html.twig');
    }

    public function wordpressAction()
    {
        return $this->render('CmsCoreBundle:Welcome:wordpress.html.twig');
    }

    public function signupAction()
    {
        return $this->render('CmsCoreBundle:Welcome:signup.html.twig');
    }

}
