<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 2:08 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller {

    public function indexAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $sites = $this->get('persister')->getRepo('CmsCoreBundle:Site')->findAll();
        $token = $this->get('csrfToken')->createToken()->getToken();

        return $this->render('CmsCoreBundle:App:index.html.twig', array(
            'user' => $user,
            'sites' => $sites,
            'token' => $token,
        ));
    }

    public function settingsAction(){
        
    }


}