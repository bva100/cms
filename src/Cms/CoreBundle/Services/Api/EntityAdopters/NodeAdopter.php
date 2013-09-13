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
            throw new RuntimeException('Node Adopter requires that a Node entity is inject. Instance of '.get_class($node).' was injected.');
        }
        $this->resource = $node;
        return $this;
    }

    /**
     * Converts entity/document node into API acceptable interface
     *
     * @return stdClass
     */
    public function convert()
    {
        $obj = new stdClass;
        $obj->id = $this->resource->getId();
        $obj->domain = $this->resource->getDomain();
        $obj->locale = $this->resource->getLocale();
        $obj->categories = $this->resource->getCategories();
        $obj->tags = $this->resource->getTags();
        $obj->slug = $this->resource->getSlug();
        $obj->title = $this->resource->getTitle();
        $obj->view = $this->resource->getViews();
        $obj->description = $this->resource->getDescription();
        $obj->metatags = $this->resource->getMetatags();
        $obj->fields = $this->resource->getFields();
        $obj->author = $this->resource->getAuthor();
        $obj->image = $this->resource->getImage();
        $obj->created = $this->resource->getCreated();
        return $obj;
    }

}