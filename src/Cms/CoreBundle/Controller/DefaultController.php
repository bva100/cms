<?php

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Cms\CoreBundle\Document\Asset;
use Cms\CoreBundle\Document\Template;
use Cms\CoreBundle\Document\Content;
use Cms\CoreBundle\Document\Node;
use Cms\CoreBundle\Document\Site;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $node = new Node();
        $node->addCategory('foo', 'bar');
        $node->addCategory('dog');
        $node->addCategory('dog', 'toys');
        $node->addTag('dog-review');
        $node->addTag('dog-toys');

        $this->get('persister')->save($node);
        return new Response('saved');
    }
}
