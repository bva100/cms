<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Cms\CoreBundle\Document\Asset;
use Cms\CoreBundle\Document\Template;
use Cms\CoreBundle\Document\Content;
use Cms\CoreBundle\Document\Node;
use Cms\CoreBundle\Document\Site;
use Cms\CoreBundle\Document\ContentType;

use Cms\CoreBundle\Services\NodeLoader\LocaleResolver;

class DefaultController extends Controller
{
    public function siteCreateAction()
    {
        $site = new Site();
        $site->setNamespace('foobartest');
        $site->setDomain('localhost');

        $reviews = new ContentType();
        $reviews->setName($site->getNamespace().'reviews');
        $reviews->addFormat('single');
        $reviews->addFormat('loop');
        $site->addContentType($reviews);

        $gallery = new ContentType();
        $gallery->setName($site->getNamespace().'gallery');
        $gallery->addFormat('single');
        $gallery->addFormat('loop');
        $site->addContentType($gallery);
        
        $results = $this->get('persister')->save($site);
        return $results ? new Response('saved with id '.$sites->getId()) : new Response('failed');
    }

    public function nodeCreateAction($host, $namespace)
    {
        // reivew en
        $review = new Node();
        $review->setSlug('five-best-dogs');
        $review->setTitle('five best dog toys');
        $review->setHost($host);
        $review->setSiteId('51b77cb518a516fd7800002c');
        $review->setLocale('en-us');
        $review->setFormat('single');
        $review->setContentTypeName($namespace.'reviews');
        $review->addCategory('dog');
        $review->addCategory('dog', 'toy');
        $review->addTag('dog');
        $review->addTag('dog-toy');
        $review->setTemplateName('Summit');
        $review->setView(array('html' => '<h1>five best dog toys</h1><p>here is a list of the five best dog toys ever</p>'));

        $results = $this->get('persister')->save($review);
//        return $results ? new Response('review saved with id '.$review->getId()) : new Response('failed');

        // review en2
        $review = new Node();
        $review->setSlug('great-dog-chew-toy');
        $review->setTitle('great dog chew toy');
        $review->setHost($host);
        $review->setSiteId('51b77cb518a516fd7800002c');
        $review->setLocale('en-us');
        $review->setContentTypeName($namespace.'reviews');
        $review->setFormat('single');
        $review->addCategory('dog');
        $review->addCategory('dog', 'toy');
        $review->addCategory('dog', 'chew');
        $review->addTag('dog');
        $review->addTag('dog-toy');
        $review->addTag('dog-chew-toy');
        $review->setTemplateName('Summit');
        $review->setView(array('html' => '<h1>Great Dog Chew Toys</h1><p>This is some copy that contains information about great doggie chew toys</p>'));

        $results = $this->get('persister')->save($review);
//        return $results ? new Response('review saved with id '.$review->getId()) : new Response('failed');


        // review es
        $reviewEs = new Node();
        $reviewEs->setSlug('five-best-dogs');
        $reviewEs->setTitle('cinco mejores juguetes para perros');
        $reviewEs->setHost($host);
        $reviewEs->setSiteId('51b77cb518a516fd7800002c');
        $reviewEs->setLocale('es-es');
        $reviewEs->setFormat('single');
        $reviewEs->setContentTypeName($namespace.'reviews');
        $reviewEs->addCategory('perro');
        $reviewEs->addCategory('perro', 'juguete');
        $reviewEs->addTag('perro');
        $reviewEs->addTag('perro-juguete');
        $reviewEs->setTemplateName('Summit');
        $reviewEs->setView(array('html' => '<h1>cinco mejores juguetes para perros</h1><p>Aquí está una lista de los cinco mejores perro juguetes siempre</p>'));

        $results = $this->get('persister')->save($reviewEs);
//        return $results ? new Response('review saved with id '.$reviewEs->getId()) : new Response('failed');

        // review loop
        $reviewLoop = new Node();
        $reviewLoop->setSlug('reviews');
        $reviewLoop->setTitle('review loop');
        $reviewLoop->setHost($host);
        $reviewLoop->setSiteId('51b77cb518a516fd7800002c');
        $reviewLoop->setLocale('en-us');
        $reviewLoop->setContentTypeName($namespace.'reviews');
        $reviewLoop->setFormat('loop-tag');
        $reviewLoop->setTemplateName('Summit');
        $reviewLoop->setView(array('html' => '<h1>review loop</h1><p>this is a loop, cool eh?</p>'));

        $results = $this->get('persister')->save($reviewLoop);
//        return $results ? new Response('review loop saved with id '.$reviewLoop->getId()) : new Response('failed');

        // painitng
        $painting = new Node();
        $painting->setSlug('blue-ocean-landscape');
        $painting->setTitle('blue ocean landscape');
        $painting->setHost($host);
        $painting->setSiteId('51b77cb518a516fd7800002c');
        $painting->setLocale('en-us');
        $painting->setFormat('single');
        $painting->setContentTypeName($namespace.'gallery');
        $painting->addCategory('landscape');
        $painting->addCategory('landscape', 'ocean');
        $painting->setTemplateName('Gallery');
        $painting->setView(array('html' => '<h1>Blue Landscape Painting</h1><p>cool painting</p><img src="wwww.foobartest.com/blue-landscape">'));

        $results = $this->get('persister')->save($painting);
//        return $results ? new Response('painting saved with id '.$painting->getId()) : new Response('failed to save');

         //review loop
        $reviewLoop = new Node();
        $reviewLoop->setSlug('reviews/dog');
        $reviewLoop->setTitle('reivew loop');
        $reviewLoop->setHost($host);
        $reviewLoop->setSiteId('51b77cb518a516fd7800002c');
        $reviewLoop->setLocale('en-us');
        $reviewLoop->setContentTypeName($namespace.'reviews');
        $reviewLoop->setFormat('loop-tag');
        $reviewLoop->setTemplateName('Summit');
        $reviewLoop->setView(array('html' => '<h1>review loop</h1><p>this is a loop, cool eh?</p>'));

        $results = $this->get('persister')->save($reviewLoop);
//        return $results ? new Response('review loop saved with id '.$reviewLoop->getId()) : new Response('failed');

        $reviewLoop = new Node();
        $reviewLoop->setSlug('reviews/dog/toy/chew');
        $reviewLoop->setTitle('reivew loop');
        $reviewLoop->setHost($host);
        $reviewLoop->setSiteId('51b77cb518a516fd7800002c');
        $reviewLoop->setLocale('en-us');
        $reviewLoop->setContentTypeName($namespace.'reviews');
        $reviewLoop->setFormat('loop-tag');
        $reviewLoop->setTemplateName('Summit');
        $reviewLoop->setView(array('html' => '<h1>review loop</h1><p>this is a loop, cool eh?</p>'));

        $results = $this->get('persister')->save($reviewLoop);
//        return $results ? new Response('review loop saved with id '.$reviewLoop->getId()) : new Response('failed');

        $reviewLoop = new Node();
        $reviewLoop->setSlug('reviews/dog/toy');
        $reviewLoop->setTitle('reivew loop');
        $reviewLoop->setHost($host);
        $reviewLoop->setSiteId('51b77cb518a516fd7800002c');
        $reviewLoop->setLocale('en-us');
        $reviewLoop->setContentTypeName($namespace.'reviews');
        $reviewLoop->setFormat('loop-category');
        $reviewLoop->setTemplateName('Summit');
        $reviewLoop->setView(array('html' => '<h1>review loop</h1><p>this is a loop with the category of dog => toy, cool eh?</p>'));

        $results = $this->get('persister')->save($reviewLoop);
//        return $results ? new Response('review loop saved with id '.$reviewLoop->getId()) : new Response('failed');


        $reviewLoop = new Node();
        $reviewLoop->setSlug('reviews/dog/toy/chew');
        $reviewLoop->setTitle('reivew loop');
        $reviewLoop->setHost($host);
        $reviewLoop->setSiteId('51b77cb518a516fd7800002c');
        $reviewLoop->setLocale('en-us');
        $reviewLoop->setContentTypeName($namespace.'reviews');
        $reviewLoop->setFormat('loop-tag');
        $reviewLoop->setTemplateName('Summit');
        $reviewLoop->setView(array('html' => '<h1>review loop</h1><p>this is a loop, cool eh?</p>'));

        $results = $this->get('persister')->save($reviewLoop);
//        return $results ? new Response('review loop saved with id '.$reviewLoop->getId()) : new Response('failed');

    }

    public function nodeReadAction(Request $request, $_locale = null, $path = null)
    {
        $nodeRepo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $params = $this->get('param_manager')->setRequest($request)->setLocale($_locale)->setPath($path)->get();
        $node = $this->get('node_loader')->setNodeRepo($nodeRepo)->setParams($params)->load();
        if ( ! $node )
        {
            throw $this->createNotFoundException('node not found');
        }
        $loop = $this->get('loop_loader')->setNode($node)->setNodeRepo($nodeRepo)->setParams($params)->load();





        echo '<pre>', \var_dump($node->getView(), '<hr>');
        if ( ! empty($loop) )
        {
            foreach ($loop as $loop) {
                $view = $loop->getView();
                echo $view['html'].'<br>';
            }
        }
        die('end');
    }
}
