<?php
/**
 * User: Brian Anderson
 * Date: 9/12/13
 * Time: 10:56 PM
 */

namespace Cms\CoreBundle\Tests\EntityAdopters;

use \PHPUnit_Framework_TestCase as PhpUnit;
use Cms\CoreBundle\Services\Api\EntityAdopters\NodeAdopter;
use Cms\CoreBundle\Document\Node as Node;

class NodeTest extends PhpUnit {

    private $node;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->node = new Node();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->node = new Node();
    }

    /**
     * @covers Cms\CoreBundle\Tests\EntityAdopters\Node::convert
     */
    public function testConvert()
    {
        $adopter = new NodeAdopter();
        $adopter->setResource($this->node);
        $obj = $adopter->convert();
        $this->assertEquals($obj->id, $this->node->getId());
        $this->assertEquals($obj->domain, $this->node->getDomain());
        $this->assertEquals($obj->locale, $this->node->getLocale());
        $this->assertEquals($obj->categories, $this->node->getCategories());
        $this->assertEquals($obj->tags, $this->node->getTags());
        $this->assertEquals($obj->slug, $this->node->getSlug());
        $this->assertEquals($obj->title, $this->node->getTitle());
        $this->assertEquals($obj->description, $this->node->getDescription());
        $this->assertEquals($obj->metatags, $this->node->getMetatags());
        $this->assertEquals($obj->fields, $this->node->getFields());
        $this->assertEquals($obj->author, $this->node->getAuthor());
        $this->assertEquals($obj->image, $this->node->getImage());
        $this->assertEquals($obj->created, $this->node->getCreated());
    }

}