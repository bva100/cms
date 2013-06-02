<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cms\CoreBundle\Services\AssetManager;
use Cms\CoreBundle\Document\Asset;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $asset = $this->get('persister')->getRepo('CmsCoreBundle:Asset')->find('51aae72518a516340400000e');
        $asset->removeOldestHistory();

        $results = $this->get('persister')->save($asset);

        $notices = $this->get('session')->getFlashBag()->get('notices');
        echo '<pre>', \var_dump($results, $notices); die();
    }
}
