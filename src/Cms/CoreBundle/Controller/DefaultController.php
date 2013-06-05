<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Cms\CoreBundle\Document\Asset;
use Cms\CoreBundle\Document\Template;
use Cms\CoreBundle\Document\Node;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $em = $this->get('doctrine_mongodb')->getManager();
        $dynamic_list_service = new \Cms\CoreBundle\Services\NodeList\DoctrineMongo($em);

        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find('51af953f18a516fd78000016');
        $dynamic_list_service->setNode($node);
        $list = $dynamic_list_service->getList();
        echo '<pre>', \var_dump(count($list)); die();


//        $node = new Node();
//        $node->addMetadata(array(
//            'slug' => 'third-node-test',
//            'categories' => array('parent' => 'test', 'sub' => 'persist', 'locale' => 'en'),
//            'locale' => 'en',
//        ));
//        $node->addMetadata(array(
//            'categories' => array('parent' => 'test', 'sub' => 'bar', 'locale' => 'en'),
//            'tags' => array('foo', 'bar', 'test'),
//        ));
//        $node->addMetadata(array(
//            'categories' => array('parent' => 'baz', 'sub' => 'foo', 'locale' => 'en'),
//        ));
//        $node->addMetadata(array(
//            'site' => array('id' => '1', 'domain' => 'foobartest.com'),
//            'type' => array('name' => 'test', 'format' => 'single'),
//        ));
//        $node->addMetadata(array(
//            'title' => 'third node test',
//            'author' => array('name' => 'brian anderson'),
//        ));
//        $node->addView(array(
//            'content' => '<h1>third node test</h1>',
//        ));
//
//        $this->get('persister')->save($node);
//        return new Response('saved');

       
//        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find('51ae8d5c18a5163504000016');
//        if ( ! $node )
//        {
//            echo '<pre>', \var_dump('node not found'); die();
//        }
//        else
//        {
//           echo '<pre>', \var_dump($node->getMetadata('defaultLocale')); die();
//        }
    }
}
