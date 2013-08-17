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

class ClientController extends Controller
{

    public function nodeReadAction(Request $request, $_locale = null, $path = null)
    {
        $nodeRepo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $params = $this->getParams($request, $_locale, $path);
        $node = $this->getNode($nodeRepo, $params);
        $loop = $this->getLoop($node, $nodeRepo, $params);
        $twig = $this->get('twig_loader')->load();
//        $twig->clearCacheFiles();
        $nameHelper = $this->get('namespace_helper')->setFullname($node->getTemplateName());
        return new Response($twig->render($node->getTemplateName(), array(
            'node' => $node,
            'loop' => $loop,
            'namespace' => $nameHelper->getNamespace(),
            'theme' => $nameHelper->getTheme(),
            'templateType' => $nameHelper->getType(),
        )));
    }

    public function apiNodeReadAction(Request $request, $_locale = null, $path = null)
    {
        $nodeRepo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $params = $this->getParams($request, $_locale, $path);
        $node = $this->getNode($nodeRepo, $params);
        $response = new Response(json_encode(array('node' => $node->getVars())));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function apiLoopReadAction(Request $request, $_locale = null, $path = null)
    {
        $nodeRepo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $params = $this->getParams($request, $_locale, $path);
        $node = $this->getNode($nodeRepo, $params);
        $loop = $this->getLoop($node, $nodeRepo, $params);
        $loopNodes = array();
        foreach ($loop as $loopNode) {
            $loopNodes[] = $loopNode->getVars();
        }
        $response = new Response(json_encode(array('loop' => $loopNodes, 'node' => $node->getVars())));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function apiExploreAction($siteId)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        return $this->render('CmsCoreBundle:Api:test.html.twig', array(
            'site' => $site,
        ));
    }
    
    public function getParams($request, $locale, $path)
    {
        return $params = $this->get('param_manager')->setRequest($request)->setLocale($locale)->setPath($path)->get();
    }

    public function getNode($nodeRepo, $params)
    {
        $node = $this->get('node_loader')->setNodeRepo($nodeRepo)->setParams($params)->load();
        if ( ! $node )
        {
            throw $this->createNotFoundException('node not found');
        }
        return $node;
    }

    public function getloop($node, $nodeRepo, $params)
    {
        return $loop = $this->get('loop_loader')->setNode($node)->setNodeRepo($nodeRepo)->setParams($params)->load();
    }
    
}
