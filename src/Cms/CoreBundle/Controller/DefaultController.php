<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Cms\CoreBundle\Services\AssetManager;
use Cms\CoreBundle\Services\TemplateLoader\MongoTwigLoader;
use Cms\CoreBundle\Document\Asset;
use Cms\CoreBundle\Document\Template;
use Cms\CoreBundle\Document\Node;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
//        $node = new Node();
//        $node->addMetadata(array(
//            'slug' => 'this-is-a-test',
//            'categories' => array('parent' => 'test', 'sub' => 'persist'),
//            'defaultLocale' => 'en',
//        ));
//        $node->addMetadata(array(
//            'categories' => array('parent' => 'test', 'sub' => 'bar'),
//            'tags' => array('foo', 'bar', 'test'),
//        ));
//        $node->addMetadata(array(
//            'categories' => array('parent' => 'baz', 'sub' => 'foo'),
//        ));
//
//        $this->get('persister')->save($node);
//        return new Response('saved');

       
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find('51ae8d5c18a5163504000016');
        if ( ! $node )
        {
            echo '<pre>', \var_dump('node not found'); die();
        }
        else
        {
           echo '<pre>', \var_dump($node->getMetadata('defaultLocale')); die();
        }
    }
}
