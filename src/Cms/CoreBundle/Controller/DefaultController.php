<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cms\CoreBundle\Services\AssetManager;
use Cms\CoreBundle\Document\Asset;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $asset = new Asset();
        $asset->setName('coco');
        $asset->setExt('css');
        $asset->setUrl('mermetmer');

        $results = $this->get('persister')->save($asset);

        $notices = $this->get('session')->getFlashBag()->get('notices');
        echo '<pre>', \var_dump($results, $notices); die();
    }
}
