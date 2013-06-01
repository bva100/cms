<?php

namespace Cms\PersisterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CmsPersisterBundle:Default:index.html.twig', array('name' => $name));
    }
}
