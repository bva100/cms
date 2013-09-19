<?php
/**
 * User: Brian Anderson
 * Date: 9/2/13
 * Time: 11:25 AM
 */

namespace Cms\CoreBundle\Controller;

use Cms\CoreBundle\Document\Node;
use Cms\CoreBundle\Services\Api\ApiException;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends ApiBaseController {

    public function nodeReadV1Action($ids, $_format)
    {
        extract($this->getDefaultVars($_format));
        $idsArray = explode(',', $ids);
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndIds($clientId, $idsArray);
        $resources = $this->getResourcesArray($nodes, $_format, $fields);
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'node', 'plural' => 'nodes'))
            ->setMeta(array('status' => 200, 'loadTime' => $stopwatch->stop('loadTime')->getDuration().' ms'))
            ->output();
    }

    public function nodeReadAllV1Action($_format)
    {
        extract($this->getDefaultVars($_format));
        $repo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $nodes = $repo->findBySiteId($clientId, $params, $options);
        $resources = $this->getResourcesArray($nodes, $_format, $fields);
        $count = $repo->findBySiteId($clientId, $params, $options, true);
        $meta = $this->createCollectionMeta($options, $count, $_format, $stopwatch->stop('loadTime'));
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('plural' => 'nodes'))
            ->setForceCollection(true)
            ->setMeta($meta)
            ->output();
    }

    public function nodeCreateAction($_format)
    {
        $objectParams = $this->getRequest()->request->get('objectParams');
        if ( ! $objectParams ){
            throw new ApiException(20003, $_format, 'Creating a node requires the "objectParams" parameter');
        }
        $objectArray = $this->decodeObjectParams($objectParams, $_format);
        $objectArray['siteId'] = $this->get('access_token')->setToken($this->getAccessToken($_format))->getClientId();
        $node = $this->get('api_node_adopter')->setResource(new Node())->getFromArray($objectArray);
        $result = $this->get('persister')->setFlashBag(null)->save($node, false, 'node created', true);
        if ( $result !== true ){
            throw new ApiException(20003, $_format, $result->getMessage());
        }
        $resources = $this->getResourcesArray(array($node), $_format);
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'node'))
            ->setMeta(array('status' => 201))
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

        //create
        $results = $PipeStack->create('nodes', array(
            'title' => 'dog test from API WITH SDK!!',
            'contentTypeName' => 'reviews',
            'domain' => 'localhost',
            'slug' => 'sdk-api',
            'views' => array(
                'html' => '<h1>ID make with access token decode</h1><p>I\'m a doggie</p>',
            ),
        ));
        echo '<pre>', \var_dump($results); die();
//        //get
//        $results = $PipeStack->get('nodes/51d8234b18a5166d3e000000');
//        echo 'response: '.$results->meta->status.'<br>';
//        if ( $results->meta->status !== 200 )
//        {
//            echo 'something wrong! '.$results->meta->message.' '.$results->meta->moreInfo; die();
//        }
        if ( is_array($results) )
        {
            foreach ($results->nodes as $node) {
                echo '<h2>', $node->title, '</h1>', '<p>', $node->view->html, '</p><br />';
            }
        }else{
            echo '<h1>', $results->node->title, '</h1>','<p>'.$results->node->views->html.'</p>';
        }

        die('<br> end');
    }

    
}