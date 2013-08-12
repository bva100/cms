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
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $user = $this->get('security.context')->getToken()->getUser();
        $sites = $this->get('persister')->getRepo('CmsCoreBundle:Site')->findAll();
        return $this->render('CmsCoreBundle:App:index.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'user' => $user,
            'sites' => $sites,
        ));
    }

    public function settingsAction(){
        
    }


}