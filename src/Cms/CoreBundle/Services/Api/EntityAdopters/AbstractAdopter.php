<?php
/**
 * User: Brian Anderson
 * Date: 9/13/13
 * Time: 10:08 AM
 */

namespace Cms\CoreBundle\Services\Api\EntityAdopters;

use Cms\CoreBundle\Document\Base;
use stdClass;

abstract class AbstractAdopter {

    protected $resource;

    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = $this->getBaseApiUrl();
    }

    public function getBaseApiUrl()
    {
        if ( ! isset($_SERVER['HTTPS']) OR ! isset($_SERVER['HTTP_HOST']) OR ! isset($_SERVER['REQUEST_URI']) )
        {
            return '';
        }
        $baseUrl = '';
        $protocol = $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://' ;
        $host = $_SERVER['HTTP_HOST'];
        if ( ! $host )
        {
            $host = 'http://localhost';
        }
        $uri = $_SERVER['REQUEST_URI'];
        $vPos = strpos($uri, '/v');
        if ( $vPos ){
            $baseUrl = substr($uri, 0, $vPos+3);
        }
        return $protocol.$host.$baseUrl;
    }

    public function addObjProperty(stdClass $obj, $property, array $fields)
    {
        if (empty($fields) OR in_array($property, $fields) )
        {
            $getterName = 'get'.ucfirst($property);
            $obj->$property = $this->resource->$getterName();
        }
        return $obj;
    }

    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param Base $resource
     * @return self
     */
    abstract public function setResource(Base $resource);

    abstract public function convert();
}