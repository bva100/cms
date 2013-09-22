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
use Symfony\Component\HttpFoundation\Response;

class ApiNodesController extends ApiBaseController {

    public function readV1Action($ids, $_format)
    {
        extract($this->getDefaultVars($_format));
        $idsArray = explode(',', $ids);
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndIds($clientId, $idsArray);
        $resources = $this->getResourcesArray($this->get('api_node_adopter'), $nodes, $_format, $fields);
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'node', 'plural' => 'nodes'))
            ->setMeta(array('status' => 200, 'loadTime' => $stopwatch->stop('loadTime')->getDuration().' ms'))
            ->output();
    }

    public function readAllV1Action($_format)
    {
        extract($this->getDefaultVars($_format));
        $repo = $this->get('persister')->getRepo('CmsCoreBundle:Node');
        $nodes = $repo->findBySiteId($clientId, $params, $options);
        $resources = $this->getResourcesArray($this->get('api_node_adopter'), $nodes, $_format, $fields);
        $count = $repo->findBySiteId($clientId, $params, $options, true);
        $meta = $this->createCollectionMeta($options, $count, $_format, $stopwatch->stop('loadTime'));
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('plural' => 'nodes'))
            ->setForceCollection(true)
            ->setMeta($meta)
            ->output();
    }

    public function createV1Action($_format)
    {
        $objectParams = $this->getRequest()->request->get('objectParams');
        if ( ! $objectParams ){
            throw new ApiException(20003, $_format, 'Creating a node requires the "objectParams" parameter. This is a json encoded array which sets property values to the new Node resource.');
        }
        $objectArray = $this->decodeObjectParams($objectParams, $_format);
        $objectArray['siteId'] = $this->get('access_token')->setToken($this->getAccessToken($_format))->getClientId();
        $node = $this->get('api_node_adopter')->setResource(new Node())->getFromArray($objectArray);
        $result = $this->get('persister')->setFlashBag(null)->save($node, false, 'node created', true);
        if ( $result !== true ){
            throw new ApiException(20003, $_format, $result->getMessage());
        }
        $resources = $this->getResourcesArray($this->get('api_node_adopter'), array($node), $_format);
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'node'))
            ->setMeta(array('status' => 201))
            ->output();
    }

    public function deleteV1Action($ids, $_format)
    {
        extract($this->getDefaultVars($_format));
        $deletedIds = array();
        $idsArray = explode(',', $ids);
        $count = count($idsArray);
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndIds($clientId, $idsArray);
        if ( count($nodes) < 1 ){
            throw new ApiException(20002, $_format);
        }
        $persister = $this->get('persister')->setFlashBag(null);
        foreach ($nodes as $node) {
            $persister->delete($node, true, $node->getId());
            $deletedIds[] = $node->getId();
        }
        $persister->flush();
        if ( empty($deletedIds) ){
            throw new ApiException(20004, $_format);
        }
        if ( $count > 1 AND count($deletedIds) < $count ){
            throw new ApiException(20003, $_format, 'All nodes were successfully removed except those with the following node IDs: '.implode(', ', array_diff($idsArray, $deletedIds)).'. If this problem persists, please report it at the link found under "moreInfo".');
        }
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    public function updateV1Action($id, $_format)
    {
        extract($this->getDefaultVars($_format));
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($id);
        if ( ! $node ){
            throw new ApiException(20002, $_format);
        }
        $objectParams = $this->getRequest()->request->get('objectParams');
        if ( ! $objectParams ){
            throw new ApiException(20003, $_format, 'Updating a node requires the "objectParams" parameter. This is a json encoded array which sets new property values to the Node resource.');
        }
        $objectArray = $this->decodeObjectParams($objectParams, $_format);
        $updatedNode = $this->get('api_node_adopter')->setResource($node)->getFromArray($objectArray);
        $result = $this->get('persister')->setFlashBag(null)->save($updatedNode, false, 'node updated', true);
        if ( $result !== true ){
            throw new ApiException(20003, $_format, $result->getMessage());
        }
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
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
    
}