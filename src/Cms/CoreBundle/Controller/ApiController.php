<?php
/**
 * User: Brian Anderson
 * Date: 9/2/13
 * Time: 11:25 AM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class ApiController extends ApiBaseController {

    public function nodeReadV1Action($ids, $_format)
    {
        extract($this->getDefaultVars($_format));
        $idsArray = explode(',', $ids);
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndIds($clientId, $idsArray);
        $resources = $this->getResourcesArray($nodes, $_format, $fields);
        return $this->get('api_output')
            ->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'node', 'plural' => 'nodes'))
            ->setMeta(array('status' => 200, 'loadTime' => $stopwatch->stop('loadTime')->getDuration().' ms'))
            ->output();
    }

    public function NodeReadAllV1Action($_format)
    {
        extract($this->getDefaultVars($_format));
        $repo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $nodes = $repo->findBySiteId($clientId, $params, $options);
        $resources = $this->getResourcesArray($nodes, $_format, $fields);
        $count = $repo->findBySiteId($clientId, $params, $options, true);
        $meta = $this->createCollectionMeta($options, $count, $_format, $stopwatch->stop('loadTime'));
        return $this->get('api_output')
            ->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('plural' => 'nodes'))
            ->setForceCollection(true)
            ->setMeta($meta)
            ->output();
    }








    public function tokenAction()
    {
        // resets secret and offers token for a client id
        $clientId = $this->getRequest()->query->get('clientId');
        $secret = $this->get('access_token')->createSecret();
        
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($clientId);
        if ( ! $site ){
            throw $this->createNotFoundException('site with id '.$clientId.' not found');
        }
        $site->setClientSecret($secret);
        $success = $this->get('persister')->save($site);
        if ( ! $success ){
            throw new \Exception('not able to update client secret');
        }
        $token = $this->get('access_token')->createToken($clientId, $secret);
        return new Response('the new token is <br> '.$token);
    }

    public function testAction()
    {
        require 'PipeStack.php';
        $accessToken = 'PzKmOxi72jxlNd3icrQJWhbMJ62BIWhl7iHA5LIS1wPu3yLJ5Gp08hDR6oZKL_wtqsTix-FFgS-2gw2wgbw5fmaIcxjklITn1BNjYLXEXLe67cRTVeA4VcRKgHjw24z1';
        $PipeStack = new \PipeStack($accessToken, 'local');
        $results = $PipeStack->get('nodes/51d8234b18a5166d3e000001,5213b15218a516290f000001');
//        $results = $PipeStack->get('node/find', array('slug' => 'review/cloud-front', 'domain' => 'localhost'));
//        $results = $PipeStack->get('node/search', array('category' => 'travel', 'domain' => 'localhost'));
//        echo '<pre>', \var_dump($results); die();
        //single
//        return new Response('<h1>'.$results->node->title.'</h1><p>'.$results->node->view->html.'</p>');
        //many
        echo 'response: '.$results->meta->code.'<br>';
        foreach ($results->nodes as $node) {
            echo '<h2>', $node->title, '</h1>', '<p>', $node->view->html, '</p><br />';
        }
        die('<br> end');
    }

    
}