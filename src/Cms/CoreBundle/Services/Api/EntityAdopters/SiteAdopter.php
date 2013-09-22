<?php


namespace Cms\CoreBundle\Services\Api\EntityAdopters;

use Cms\CoreBundle\Document\Base;
use Cms\CoreBundle\Document\Site;
use Cms\CoreBundle\Services\Api\EntityAdopters\AbstractAdopter;
use stdClass;

class SiteAdopter extends AbstractAdopter {

    /**
     * Return a converted entity object. Original Document to stdClass object.
     *
     * @param array $fields
     * @return stdClass
     */
    public function convert(array $fields = array())
    {
        $obj = new stdClass;
        $obj = $this->addObjProperty($obj, 'id', $fields);
        $obj = $this->addObjProperty($obj, 'name', $fields);
        $obj = $this->addObjProperty($obj, 'namespace', $fields);
        $obj = $this->addObjProperty($obj, 'domains', $fields);
        if ( isset($obj->id) ){
            $obj->_links = array('self' => array('href' => $this->getBaseApiUrl().'/sites.'.$this->getFormat()));
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
        if ( isset($namespace) ){
            $this->resource->setNamespace($namespace);
        }
        if ( isset($domains) ){
            $this->resource->setDomains($domains);
        }
        return $this->resource;
    }

    /**
     * @param Base $site
     * @return $this|AbstractAdopter
     * @throws \RuntimeException
     */
    public function setResource(Base $site)
    {
        if ( ! $site instanceof Site ){
            throw new \RuntimeException('Site Adopter requires that a Site entity is injected. Instance of '.get_class($site).' was injected.');
        }
        
        $this->resource = $site;
        return $this;
    }




}