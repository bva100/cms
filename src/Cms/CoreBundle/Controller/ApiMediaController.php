<?php


namespace Cms\CoreBundle\Controller;


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

    public function getMediaRepo()
    {
        return $this->get('persister')->getRepo('CmsCoreBundle:Media');
    }

}