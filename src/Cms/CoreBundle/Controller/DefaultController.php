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
        $twig = $this->get('twig_loader')->load();
//        $twig->clearCacheFiles();
        echo '<pre>', \var_dump($loop); die();
        $nameHelper = $this->get('namespace_helper')->setFullname($node->getTemplateName());
        return new Response($twig->render($node->getTemplateName(), array(
            'node' => $node,
            'loop' => $loop,
            'namespace' => $nameHelper->getNamespace(),
            'theme' => $nameHelper->getTheme(),
            'templateType' => $nameHelper->getType(),
        )));
    }
}
