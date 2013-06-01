<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Cms\CoreBundle\Services\AssetManager;
use Cms\CoreBundle\Document\Asset;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $em = $this->get('doctrine_mongodb')->getManager();

        $asset = new Asset();
        $asset->setName('testMe!');
        $asset->setType('css');
        $asset->setAcl( array('brian' => array('write', 'read')));
        $asset->setHistory( array(time() => 'body p { font-size: 30px }', time()-100 => 'body p {font-size: 42px}') );

        $em->persist($asset);
        $em->flush();
        
        echo '<pre>', \var_dump('end'); die();
    }
}
