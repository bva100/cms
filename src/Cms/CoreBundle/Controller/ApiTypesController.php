<?php


namespace Cms\CoreBundle\Controller;

use Cms\CoreBundle\Document\ContentType;
use Cms\CoreBundle\Services\Api\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTypesController extends ApiBaseController {

    public function readV1Action($ids, $_format)
    {
        extract($this->getDefaultVars($_format));
        $idsArray = explode(',', $ids);
        $types = array();
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($clientId);
        foreach ($idsArray as $id) {
            if ( $type = $site->getContentType($id) ){
                $types[] = $type;
            }
        }
        $resources = $this->getResourcesArray($this->get('api_contentType_adopter'), $types, $_format, $fields);
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'type', 'plural' => 'types'))
            ->setMeta(array('status' => 200, 'loadTime' => $stopwatch->stop('loadTime')->getDuration().' ms'))
            ->output();
    }

    public function readAllV1Action($_format)
    {
        extract($this->getDefaultVars($_format));
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($clientId);
        $types = $site->getContentTypes();
        $resources = $this->getResourcesArray($this->get('api_contentType_adopter'), $types, $_format, $fields);
        $count = count($site->getContentTypes());
        $meta = $this->createCollectionMeta($options, $count, $_format, $stopwatch->stop('loadTime'));
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('plural' => 'types'))
            ->setForceCollection(true)
            ->setMeta($meta)
            ->output();
    }

    public function createV1Action($_format)
    {
        $objectArray = $this->decodeObjectParams($this->getRequest(), $_format);
        $objectArray['siteId'] = $this->get('access_token')->setToken($this->getAccessToken($_format))->getClientId();
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($objectArray['siteId']);
        $type = $this->get('api_contentType_adopter')->setResource(new ContentType())->getFromArray($objectArray);
        $site->addContentType($type);
        $result = $this->get('persister')->setFlashBag(null)->save($site, false, 'type created', true);
        if ( $result !== true ){
            throw new ApiException(40003, $_format, $result->getMessage());
        }
        $resources = $this->getResourcesArray($this->get('api_contentType_adopter'), array($type), $_format);
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'type'))
            ->setMeta(array('status' => 201))
            ->output();
    }

    public function deleteV1Action($id, $_format)
    {
        extract($this->getDefaultVars($_format));
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($clientId);
        $type = $site->getContentType($id);
        if ( ! $type ){
            throw new ApiException(40002, $_format);
        }
        $site->removeContentType($type);
        $results = $this->get('persister')->setFlashBag(null)->delete($type, false);
        if ( ! $results){
            throw new ApiException(10003, $_format);
        }
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    public function updateV1Action($id, $_format)
    {
        extract($this->getDefaultVars($_format));
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($clientId);
        $type = $site->getContentType($id);
        if ( ! $type ){
            throw new ApiException(40002, $_format);
        }
        $objectArray = $this->decodeObjectParams($this->getRequest(), $_format);
        $updatedType = $this->get('api_contentType_adopter')->setResource($type)->getFromArray($objectArray);
        $result = $this->get('persister')->setFlashBag(null)->save($updatedType, false, 'type updated', true);
        if ( $result !== true ){
            throw new ApiException(40003, $_format, $result->getMessage());
        }
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

}