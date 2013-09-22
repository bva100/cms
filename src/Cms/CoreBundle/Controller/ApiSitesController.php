<?php


namespace Cms\CoreBundle\Controller;

use Cms\CoreBundle\Document\Site;
use Cms\CoreBundle\Services\Api\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiSitesController extends ApiBaseController {

    public function readV1Action($_format)
    {
        extract($this->getDefaultVars($_format));
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($clientId);
        $resource = $this->get('api_site_adopter')->setResource($site)->setFormat($_format)->convert($fields);
        return $this->get('api_output')->setFormat($_format)
            ->setResources(array($resource))
            ->setResourceNames(array('singular' => 'sites'))
            ->setMeta(array('status' => 200, 'loadTime' => $stopwatch->stop('loadTime')->getDuration().' ms'))
            ->output();
    }

    public function updateV1Action($_format)
    {
        extract($this->getDefaultVars($_format));
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($clientId);
        $objectParams = $this->getRequest()->request->get('objectParams');
        if ( ! $objectParams ){
            throw new ApiException(30003, $_format, 'Updating a site requires the "objectParams" parameter. This is a json encoded array which sets new property values to the Site resource.');
        }
        $objectArray = $this->decodeObjectParams($objectParams, $_format);
        $updatedSite = $this->get('api_site_adopter')->setResource($site)->getFromArray($objectArray);
        $result = $this->get('persister')->setFlashBag(null)->save($updatedSite, false, 'site updated', true);
        if ( $result !== true ){
            throw new ApiException(30003, $_format, $result->getMessage());
        }
        $response = new Response();
        $response->setStatusCode(204);
        return $response;
    }

}