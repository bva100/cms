<?php

namespace Cms\CoreBundle\Controller;

require_once 'pipestack-sdk-php-master/sdk/PipeStackFactory.php';

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DevelopersController extends Controller {

    public function indexAction()
    {
        return $this->renderNode('intro');
    }

    public function basicsAction()
    {
        return $this->renderNode('basics');
    }

    public function responsesAction()
    {
        return $this->renderNode('responses');
    }

    public function exceptionsAction()
    {
        return $this->renderNode('exceptions');
    }

    public function endpointNodesAction()
    {
        return $this->renderNode('nodes');
    }

    public function endpointMediaAction()
    {
        return $this->renderNode('media');
    }

    public function endpointTypesAction()
    {
        return $this->renderNode('types');
    }

    public function endpointSitesAction()
    {
        return $this->renderNode('sites');
    }

    public function sdkPhpAction()
    {
        return $this->renderNode('sdk/php');
    }

    public function demosAction()
    {
        return $this->renderNode('demos');
    }

    public function renderNode($docName)
    {
        $pipestack = \PipeStackFactory::build('Prod');
        try{
            $response = $pipestack->get('nodes?slug=docs/'.$docName);
        }catch(\PipeStackException $e){
            throw new \Exception($e->getMessage());
        }
        $nodes = $response->nodes;
        return $this->render('CmsCoreBundle:Developers:index.html.twig', array('node' => $nodes[0], 'doc' => $docName));
    }

}