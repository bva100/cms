<?php


namespace Cms\CoreBundle\Controller;

use Cms\CoreBundle\Document\Site;
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
            $types[] = $site->getContentType($id);
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

}