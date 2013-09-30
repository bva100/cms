<?php


namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelpController extends Controller {

    public function indexAction()
    {
        return $this->render("CmsCoreBundle:Help:index.html.twig");
    }

}