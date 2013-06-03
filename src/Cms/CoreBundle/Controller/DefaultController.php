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
        $asset = $this->get('persister')->getRepo('CmsCoreBundle:Asset')->find('51aaed5818a516fd7800000c');
        echo '<pre>', \var_dump($asset); die();
    }
}
