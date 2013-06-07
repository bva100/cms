<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Cms\CoreBundle\Document\Asset;
use Cms\CoreBundle\Document\Template;
use Cms\CoreBundle\Document\Node;
use Cms\CoreBundle\Document\Site;
use Cms\CoreBundle\Document\ContentType;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
//        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find('51af953f18a516fd78000016');
//        $list = $this->get('dynamic_nodes')->setNode($node)->getList();
//        foreach ($list as $list) {
//            echo $list->getMetadata('title').'<br>';
//        }
//        die('end');

//        $em = $this->get('doctrine_mongodb')->getManager();
//        $evm = $em->getEventManager();
//        echo '<pre>', \var_dump($evm->addEventListener()); die();


//        $site = new Site();
//        $site->setName('test site');
//        $site->setDomain('www.testsite.com');
//        $site->setTemplateName('Summit');
//        $site->setNamespace('coreTestSite');
//
//        $contentType = new ContentType();
//        $contentType->setName('a new test content type');
//        $contentType->setFormats(array('loop', 'single'));
//        $contentType->setSlugPrefix('testSite');
//        $contentType->setLimit(8);
//        $contentType->setTaxonomyStyle('categories');
//        $site->addContentType($contentType);
//
//        $this->get('persister')->save($site);
//        return new Response('saved '.$site->getId());

//        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find('51b0e2a718a516fd78000018');
//        if ( ! $site )
//        {
//            return new Response('Site not found');
//        }
//        $contentTypes = $site->getContentType();
//        foreach ($contentTypes as $contentType) {
//            $contentType->setName('BigRedDog');
//        }
//        $this->get('persister')->save($site);
//        die('end');

//        $node = new Node();
//        $node->addMetadata(array(
//            'slug' => 'eigth-node-test',
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
//        ));
//        $node->addMetadata(array(
//            'title' => 'eigth node test',
//            'author' => array('name' => 'brian anderson'),
//        ));
//        $node->addView(array(
//            'content' => '<h1>eigth node test</h1>',
//        ));
//
//        $contentTypes = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find('51b0e2a718a516fd78000018')->getContentTypes();
//        if ( ! $contentTypes )
//        {
//            throw $this->createNotFoundException();
//        }
//        if ( isset($contentTypes[0]) )
//        {
//            $node->setContentType($contentTypes['0']);
//        }
//
//        $this->get('persister')->save($node);
//        return new Response('saved');


        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find('51b0e2a718a516fd78000018');
        $contentTypes = $site->getContentTypes();
        $contentType = $contentTypes[0];

        $this->get('persister')->save($contentType);

//        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find('51b1851618a516350400001d');
//        echo '<pre>', \var_dump($node->getContentType()->getName()); die();
//        $node->setContentType($contentType);
//        $this->get('persister')->save($node);
//        return new Response('saved');
    }
}
