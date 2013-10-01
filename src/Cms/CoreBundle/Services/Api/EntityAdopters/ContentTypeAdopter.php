<?php


namespace Cms\CoreBundle\Services\Api\EntityAdopters;


use Cms\CoreBundle\Document\Base;
use Cms\CoreBundle\Document\ContentType;
use Cms\CoreBundle\Services\Api\EntityAdopters\AbstractAdopter;
use stdClass;

class ContentTypeAdopter extends AbstractAdopter {

    /**
     * @param Base $contentType
     * @return $this|AbstractAdopter
     * @throws \RuntimeException
     */
    public function setResource(Base $contentType)
    {
        if ( ! $contentType instanceof ContentType ){
            throw new \RuntimeException('Content Type Adopter requires that a ContentType entity is injected. Instance of '.get_class($contentType).' was injected.');
        }
        $this->resource = $contentType;
        return $this;
    }

    /**
     * Return a converted entity object. Original Document to stdClass object.
     *
     * @param array $fields
     * @return stdClass
     */
    public function convert(array $fields = array())
    {
        $obj = new stdClass;
        $this->addObjProperty($obj, 'id', $fields);
        $this->addObjProperty($obj, 'name', $fields);
        $this->addObjProperty($obj, 'description', $fields);
        $this->addObjProperty($obj, 'slugPrefix', $fields);
        $this->addObjProperty($obj, 'fields', $fields);
        $this->addObjProperty($obj, 'categories', $fields);
        $this->addObjProperty($obj, 'tags', $fields);
        $this->addObjProperty($obj, 'created', $fields);
        $this->addObjProperty($obj, 'updated', $fields);
        if ( isset($obj->id) ){
            $obj->_links = array('self' => array('href' => $this->getBaseApiUrl().'/types/'.$obj->id.'.'.$this->getFormat()));
        }
        return $obj;
    }

    /**
     * Return a set resource (base document) from an array of parameters.
     *
     * @param array $params
     * @return mixed
     */
    public function getFromArray(array $params)
    {
        extract($params);
        if ( isset($name) ){
            $this->resource->setName($name);
        }
        if ( isset($description) ){
            $this->resource->setDescription($description);
        }
        if ( isset($slugPrefix) ){
            $this->resource->setSlugPrefix($slugPrefix);
        }
        if ( isset($fields) ){
            $this->resource->setFields($fields);
        }
        return $this->getResource();
    }


}