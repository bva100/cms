<?php


namespace Cms\CoreBundle\Services\Api\EntityAdopters;


use Cms\CoreBundle\Document\Base;
use Cms\CoreBundle\Document\Media;
use stdClass;

class MediaAdopter extends AbstractAdopter {

    /**
     * @param Base $media
     * @return $this|AbstractAdopter
     * @throws \RuntimeException
     */
    public function setResource(Base $media)
    {
        if ( ! $media instanceof Media ){
            throw new \RuntimeException('Node Adopter requires that a Node entity is injected. Instance of '.get_class($node).' was injected.');
        }
        $this->resource = $media;
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
        $this->addObjProperty($obj, 'storage', $fields);
        $this->addObjProperty($obj, 'url', $fields);
        $this->addObjProperty($obj, 'mime', $fields);
        $this->addObjProperty($obj, 'ext', $fields);
        $this->addObjProperty($obj, 'size', $fields);
        $this->addObjProperty($obj, 'metadata', $fields);
        $this->addObjProperty($obj, 'nodeIds', $fields);
        $this->addObjProperty($obj, 'fields', $fields);
        $this->addObjProperty($obj, 'created', $fields);
        $this->addObjProperty($obj, 'updated', $fields);
        if ( isset($obj->id) ){
            $obj->_links = array('self' => array('href' => $this->getBaseApiUrl().'/media/'.$obj->id.'.'.$this->getFormat()));
        }
        return $obj;
    }

    /**
     * Return a set resource (base document) from an array of parameters.
     *
     * @param array $params
     * @return Media
     */
    public function getFromArray(array $params)
    {
        extract($params);
        if ( isset($filename) ){
            $this->resource->setFilename($filename);
        }
        if ( isset($storage) ){
            $this->resource->setStorage($storage);
        }
        if ( isset($url) ){
            $this->resource->setUrl($url);
        }
        if ( isset($mime) ){
            $this->resource->setMime($mime);
        }
        if ( isset($ext) ){
            $this->resource->setExt($ext);
        }
        if ( isset($size) ){
            $this->resource->setSize($size);
        }
        if ( isset($metadata) ){
            $this->resource->setMetadata($metadata);
        }
        if ( isset($nodeIds) ){
            $this->resource->setNodeIds($nodeIds);
        }
        if ( isset($fields) ){
            $this->resource->setFields($fields);
        }
        if ( isset($created) ){
            $this->resource->setCreated($created);
        }
        return $this->resource;
    }


}