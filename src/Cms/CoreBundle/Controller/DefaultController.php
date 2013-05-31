<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CmsCoreBundle:Default:index.html.twig', array('name' => 'dude, '.$name));
    }
}
