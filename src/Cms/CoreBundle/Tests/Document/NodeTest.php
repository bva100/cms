<?php
/**
 * User: Brian Anderson
 * Date: 6/4/13
 * Time: 10:18 AM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\Node;

/**
 * Class NodeTest
 * @package Cms\CoreBundle\Tests
 */
class NodeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cms\CoreBundle\Document\Node
     */
    private $node;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->node = new Node();
    }

    public function setUp()
    {
        $this->node = new Node();
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::addCategory
     * @covers \Cms\CoreBundle\Document\Node::getCategories
     * @covers \Cms\CoreBundle\Document\Node::removeCategory
     */
    public function testAddCategory()
    {
        $this->node->addCategory('foo', 'bar');
        $categories = $this->node->getCategories();
        $this->assertCount(1, $categories);
        $this->assertEquals(array('parent' => 'foo', 'sub' => 'bar'), $categories[0]);

        $this->node->addCategory('foo', 'bar');
        $categories = $this->node->getCategories();
        $this->assertCount(1, $this->node->getCategories());
        $this->assertEquals(array('parent' => 'foo', 'sub' => 'bar'), $categories[0]);

        $this->node->addCategory('hello, world', 'PHP is cool');
        $this->assertCount(2, $this->node->getCategories());
        $this->assertEquals(array(0 => array('parent' => 'foo', 'sub' => 'bar'), 1 => array('parent' => 'hello, world', 'sub' => 'PHP is cool') ), $this->node->getCategories());

        $this->node->addCategory('dog');
        $this->assertCount(3, $this->node->getCategories());

        $this->node->addCategory('dog');
        $this->assertCount(3, $this->node->getCategories());

        $this->node->addCategory('dog', 'toys');
        $this->assertCount(4, $this->node->getCategories());

        $this->node->addCategory(1);
        $this->assertCount(4, $this->node->getCategories());
        $this->node->addCategory(array());
        $this->assertCount(4, $this->node->getCategories());
        $this->node->addCategory(new \stdClass(), 'foobar');
        $this->assertCount(4, $this->node->getCategories());
        $this->node->addCategory(null);
        $this->assertCount(4, $this->node->getCategories());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::removeCategory
     * @covers \Cms\CoreBundle\Document\Node::getCategories
     * @covers \Cms\CoreBundle\Document\Node::addCategory
     */
    public function testRemoveCategory()
    {
        $this->node->addCategory('foo');
        $this->node->addCategory('hello', 'world');
        $this->node->addCategory('dog', 'toys');
        $this->assertCount(3, $this->node->getCategories());

        $this->node->removeCategory('foo');
        $this->assertCount(2, $this->node->getCategories());
        $this->node->removeCategory('hello', 'world');
        $this->assertCount(1, $this->node->getCategories());

        $this->node->removeCategory('hello');
        $this->assertCount(1, $this->node->getCategories());

        $this->node->removeCategory('hello', 'world');
        $this->assertCount(1, $this->node->getCategories());

        $this->node->removeCategory('dog', 'toys');
        $this->assertEmpty($this->node->getCategories());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::addTag
     * @covers \Cms\CoreBundle\Document\Node::getTags
     */
    public function testAddTag()
    {
        $this->node->addTag('foo');
        $this->node->addTag('bar');
        $this->node->addTag('foobar');
        $this->assertCount(3, $this->node->getTags());

        $this->node->addTag('foo');
        $this->assertCount(3, $this->node->getTags());
        $this->node->addTag('foobar');
        $this->assertCount(3, $this->node->getTags());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::addView
     * @covers \Cms\CoreBundle\Document\Node::getView
     */
    public function testAddViewAndGetView()
    {
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::removeView
     * @covers \Cms\CoreBundle\Document\Node::removeAllViews
     */
    public function testRemoveView()
    {
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::updateSlugPrefix
     * @covers \Cms\CoreBundle\Document\Node::addMetadata
     * @covers \Cms\CoreBundle\Document\Node::getMetadata
     */
    public function testSlugPrefixUpdate()
    {
    }

}