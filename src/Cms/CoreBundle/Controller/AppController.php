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
        $user = $this->getUser();
        $sites = $this->get('persister')->getRepo('CmsCoreBundle:Site')->findBySiteIdsAndState($user->getSiteIds(), 'active');
        return $this->render('CmsCoreBundle:App:index.html.twig', array(
            'token' => $this->get('csrfToken')->createToken()->getToken(),
            'notices' => $this->get('session')->getFlashBag()->get('notices'),
            'user' => $user,
            'sites' => $sites,
        ));
    }

    public function thanksAction($accountPlan)
    {
        $this->get('session')->getFlashBag()->clear();
        return $this->render('CmsCoreBundle:App:thanks.html.twig', array(
            'user' => $this->getUser(),
            'accountPlan' => $accountPlan
        ));
    }

}