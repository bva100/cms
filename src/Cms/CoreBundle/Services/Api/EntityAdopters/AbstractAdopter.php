<?php
/**
 * User: Brian Anderson
 * Date: 9/13/13
 * Time: 10:08 AM
 */

namespace Cms\CoreBundle\Services\Api\EntityAdopters;

use Cms\CoreBundle\Document\Base as Document;
use Cms\CoreBundle\Services\Api\Base as Base;
use stdClass;

abstract class AbstractAdopter extends Base {

    protected $resource;

    /**
     * Add a property to the stdClass object
     *
     * @param stdClass $obj
     * @param $property
     * @param array $fields
     * @return stdClass
     */
    public function addObjProperty(stdClass $obj, $property, array $fields)
    {
        if (empty($fields) OR in_array($property, $fields) )
        {
            $getterName = 'get'.ucfirst($property);
            $obj->$property = $this->resource->$getterName();
        }
        return $obj;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param Document $resource
     * @return self
     */
    abstract public function setResource(Document $resource);

    /**
     * Return a converted entity object. Original Document to stdClass object.
     *
     * @return stdClass
     */
    abstract public function convert();

    /**
     * Return a set resource (base document) from an array of parameters.
     *
     * @param array $params
     * @return mixed
     */
    abstract public function getFromArray(array $params);
}