<?php
/**
 * User: Brian Anderson
 * Date: 9/12/13
 * Time: 10:50 PM
 */

namespace Cms\CoreBundle\Services\Api\EntityAdopters;

use Cms\CoreBundle\Document\Node;
use Cms\CoreBundle\Document\Base;
use stdClass;
use RuntimeException;

/**
 * Class Node
 * @package Cms\CoreBundle\Services\Api\EntityAdopters
 */
class NodeAdopter extends AbstractAdopter {

    /**
     * Inject the Node entity to be converted
     *
     * @param Base $node
     * @return NodeAdopter
     * @throws \RuntimeException
     */
    public function setResource(Base $node)
    {
        if ( ! $node instanceof Node )
        {
            throw new RuntimeException('Node Adopter requires that a Node entity is injected. Instance of '.get_class($node).' was injected.');
        }
        $this->resource = $node;
        return $this;
    }

    /**
     * Converts entity/document node into API acceptable interface
     *
     * @param array $fields
     * @return stdClass
     */
    public function convert(array $fields = array())
    {
        $obj = new stdClass;
        $obj = $this->addObjProperty($obj, 'id', $fields);
        $obj = $this->addObjProperty($obj, 'domain', $fields);
        $obj = $this->addObjProperty($obj, 'locale', $fields);
        $obj = $this->addObjProperty($obj, 'categories', $fields);
        $obj = $this->addObjProperty($obj, 'tags', $fields);
        $obj = $this->addObjProperty($obj, 'slug', $fields);
        $obj = $this->addObjProperty($obj, 'title', $fields);
        $obj = $this->addObjProperty($obj, 'views', $fields);
        $obj = $this->addObjProperty($obj, 'description', $fields);
        $obj = $this->addObjProperty($obj, 'metatags', $fields);
        $obj = $this->addObjProperty($obj, 'fields', $fields);
        $obj = $this->addObjProperty($obj, 'author', $fields);
        $obj = $this->addObjProperty($obj, 'image', $fields);
        $obj = $this->addObjProperty($obj, 'created', $fields);
        if ( isset($obj->id) ){
            $obj->_links = array('self' => array('href' => $this->getBaseApiUrl().'/nodes/'.$obj->id.'.'.$this->getFormat()));
        }
        return $obj;
    }

    public function getFromArray(array $params)
    {
        extract($params);
        if ( isset($siteId) )
        {
            $this->resource->setSiteId($siteId);
        }
        if ( isset($domain) )
        {
            $this->resource->setDomain($domain);
        }
        if ( isset($locale) )
        {
            $this->resource->setLocale($locale);
        }
        if ( isset($categories) )
        {
            $this->resource->setCategories($categories);
        }
        if ( isset($tags) )
        {
            $this->resource->setTags($tags);
        }
        if ( isset($slug) )
        {
            $this->resource->setSlug($slug);
        }
        if ( isset($title) )
        {
            $this->resource->setTitle($title);
        }
        if ( isset($views) )
        {
            $this->resource->setViews($views);
        }
        if ( isset($description) )
        {
            $this->resource->setDescription($description);
        }
        if ( isset($metatags) )
        {
            $this->resource->setMetatags($metatags);
        }
        if ( isset($fields) )
        {
            $this->resource->setFields($fields);
        }
        if ( isset($author) )
        {
            $this->resource->setAuthor($author);
        }
        if ( isset($image) )
        {
            $this->resource->setImage($image);
        }
        $this->resource->setFormat( isset($format) ? $format : 'single' );
        return $this->resource;
    }

}