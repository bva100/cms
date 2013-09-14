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