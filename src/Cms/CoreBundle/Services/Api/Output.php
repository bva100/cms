<?php
/**
 * User: Brian Anderson
 * Date: 9/13/13
 * Time: 11:06 AM
 */

namespace Cms\CoreBundle\Services\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Cms\CoreBundle\Services\Api\ApiException;

class Output {

    /**
     * @var string
     */
    private $format;

    /**
     * @var array
     */
    private $meta;

    /**
     * @var array
     */
    private $notifications;

    /**
     * @var array
     */
    private $resourceNames;

    /**
     * @var array
     */
    private $resources;

    /**
     * @var bool
     */
    private $forceCollection;

    public function __construct()
    {
        $this->setMeta(array('code' => 200));
        $this->setNotifications(array());
        $this->setForceCollection(false);
    }

    /**
     * @param $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @param array $notifications
     * @return $this
     */
    public function setNotifications(array $notifications)
    {
        $this->notifications = $notifications;
        return $this;
    }

    /**
     * @return array
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param array $resourceNames
     * @return $this
     */
    public function setResourceNames(array $resourceNames)
    {
        $this->resourceNames = $resourceNames;
        return $this;
    }

    /**
     * @return array
     */
    public function getResourceNames()
    {
        return $this->resourceNames;
    }

    /**
     * @param array $resources
     * @return $this
     */
    public function setResources(array $resources)
    {
        $this->resources = $resources;
        return $this;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param bool $forceCollection
     * @return $this
     */
    public function setForceCollection($forceCollection)
    {
        $this->forceCollection = $forceCollection;
        return $this;
    }

    /**
     * @return bool
     */
    public function getForceCollection()
    {
        return $this->forceCollection;
    }

    /**
     * Check for empty resource array
     * If a single resource is found, change array of resource objects to single resource object
     * returns either a singular or plural name
     *
     * @return string
     * @throws ApiException
     */
    public function checkResourcesAndGetName()
    {
        if ( empty($this->resources) ){
            throw new ApiException(404, $this->format);
        }
        else if( ! $this->getForceCollection() AND count($this->resources) === 1){
            $resourceName = $this->resourceNames['singular'];
            $this->resources = $this->resources[0];
        }
        else {
            $resourceName = $this->resourceNames['plural'];
        }
        return $resourceName;
    }
    
    public function output()
    {
        $resourceName = $this->checkResourcesAndGetName();
        switch($this->format){
            case 'application/json':
            default:
                $response = new JsonResponse();
                $response->setData(array($resourceName => $this->resources, 'meta' => $this->meta, 'notifications' => $this->notifications));
                $response->setStatusCode($this->meta['code']);
                return $response;
                break;
        }
    }

}