<?php
/**
 * User: Brian Anderson
 * Date: 9/12/13
 * Time: 10:50 PM
 */

namespace Cms\CoreBundle\Services\Api\EntityAdopters;

use Cms\CoreBundle\Document\Node;
use stdClass;

/**
 * Class Node
 * @package Cms\CoreBundle\Services\Api\EntityAdopters
 */
class NodeAdopter implements InterfaceAdopter {

    /**
     * @var Node;
     */
    private $node;

    /**
     * Set Node entity/document
     *
     * @param Node $node
     * @return $this
     */
    public function setNode(Node $node)
    {
        $this->node = $node;
        return $this;
    }

    /**
     * Get Node entity/document
     *
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * Converts entity/document node into API acceptable interface
     *
     * @return stdObj
     */
    public function convert()
    {
        $obj = new stdClass;
        $obj->id = $this->node->getId();
        $obj->domain = $this->node->getDomain();
        $obj->locale = $this->node->getLocale();
        $obj->categories = $this->node->getCategories();
        $obj->tags = $this->node->getTags();
        $obj->slug = $this->node->getSlug();
        $obj->title = $this->node->getTitle();
        $obj->description = $this->node->getDescription();
        $obj->metatags = $this->node->getMetatags();
        $obj->fields = $this->node->getFields();
        $obj->author = $this->node->getAuthor();
        $obj->image = $this->node->getImage();
        $obj->created = $this->node->getCreated();
        return $obj;
    }

}