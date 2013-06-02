<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Cms\CoreBundle\Services\AssetManager;
use Cms\CoreBundle\Services\TemplateLoader\MongoTwigLoader;
use Cms\CoreBundle\Document\Asset;
use Cms\CoreBundle\Document\Template;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
//        $em = $this->get('doctrine_mongodb')->getManager();
//        $class = 'CmsCoreBundle:Template';
//        $loader = new MongoTwigLoader($em, $class);
//        echo '<pre>', \var_dump($loader->getSource('Summit')); die();
        $twig = $this->get('twig_loader')->load();
        return new Response($twig->render('Summit'));
    }
}
