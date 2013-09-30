<?php


namespace Cms\CoreBundle\Controller;


use Cms\CoreBundle\Document\Media;
use Cms\CoreBundle\Services\Api\ApiException;
use Symfony\Component\HttpFoundation\Response;

class ApiMediaController extends ApiBaseController {

    public function readV1Action($ids, $_format)
    {
        extract($this->getDefaultVars($_format));
        $idsArray = explode(',', $ids);
        $media = $this->getMediaRepo()->findBySiteIdAndIds($clientId, $idsArray);
        $resources = $this->getResourcesArray($this->get('api_media_adopter'), $media, $_format, $fields);
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'media', 'plural' => 'media'))
            ->setMeta(array('status' => 200, 'loadTime' => $stopwatch->stop('loadTime')->getDuration().' ms'))
            ->output();
    }

    public function readAllV1Action($_format)
    {
        extract($this->getDefaultVars($_format));
        $media = $this->getMediaRepo()->findAllBySiteId($clientId, $params, $options);
        $resources = $this->getResourcesArray($this->get('api_media_adopter'), $media, $_format, $fields);
        $count = $this->getMediaRepo()->findAllBySiteId($clientId, $params, $options, true);
        $meta = $this->createCollectionMeta($options, $count, $_format, $stopwatch->stop('loadTime'));
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('plural' => 'media'))
            ->setForceCollection(true)
            ->setMeta($meta)
            ->output();
    }

    public function createV1Action($_format)
    {
        $objectArray = $this->decodeObjectParams($this->getRequest(), $_format);
        $objectArray['siteId'] = $this->get('access_token')->setToken($this->getAccessToken($_format))->getClientId();
        $media = $this->get('api_media_adopter')->setResource(new Media())->getFromArray($objectArray);
        $media->setSiteId($this->get('access_token')->setToken($this->getAccessToken($_format))->getClientId());
        $result = $this->get('persister')->setFlashBag(null)->save($media, false, 'media created', true);
        if ( $result !== true ){
            throw new ApiException(20003, $_format, $result->getMessage());
        }
        $resources = $this->getResourcesArray($this->get('api_media_adopter'), array($media), $_format);
        return $this->get('api_output')->setFormat($_format)
            ->setResources($resources)
            ->setResourceNames(array('singular' => 'media'))
            ->setMeta(array('status' => 201))
            ->output();
    }

    public function updateV1Action($id, $_format)
    {
        extract($this->getDefaultVars($_format));
        $media = $this->getMediaRepo()->find($id);
        if ( ! $media ){
            throw new ApiException(50002, $_format);
        }
        $objectArray = $this->decodeObjectParams($this->getRequest(), $_format);
        $updatedMedia = $this->get('api_media_adopter')->setResource($media)->getFromArray($objectArray);
        $result = $this->get('persister')->setFlashBag(null)->save($updatedMedia, false, 'media updated', true);
        if ( $result !== true ){
            throw new ApiException(50003, $_format, $result->getMessage());
        }
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    public function deleteV1Action($id, $_format)
    {
        extract($this->getDefaultVars($_format));
        $media = $this->getMediaRepo()->find($id);
        if ( ! $media ){
            throw new ApiException(50002, $_format);
        }
        $results = $this->get('persister')->setFlashBag(null)->delete($media, false);
        if ( ! $results){
            throw new ApiException(50003, $_format);
        }
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

    public function getMediaRepo()
    {
        return $this->get('persister')->getRepo('CmsCoreBundle:Media');
    }

}