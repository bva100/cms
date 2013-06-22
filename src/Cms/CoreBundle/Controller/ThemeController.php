<?php
/**
 * User: Brian Anderson
 * Date: 6/22/13
 * Time: 10:44 AM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Theme;

class ThemeController {

    public function readAllAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $themes = $this->get('persister')->getRepo('CmsCoreBundle:Theme')->findAll();
        return $this->render('CmsCoreBundle:Theme:index.html.twig', array(
            'themes' => $themes,
        ));
    }



}