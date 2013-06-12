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
     * @covers \Cms\CoreBundle\Document\Node::removeTag
     * @covers \Cms\CoreBundle\Document\Node::addTag
     * @covers \Cms\CoreBundle\Document\Node::getTags
     */
    public function testRemoveTag()
    {
        $this->node->addTag('foo');
        $this->node->addTag('bar');
        $this->node->addTag('foobar');
        $this->assertCount(3, $this->node->getTags());

        $this->node->removeTag('foo');
        $this->assertCount(2, $this->node->getTags());
        $this->assertEquals(array('bar', 'foobar'), $this->node->getTags());
        $this->node->removeTag('doesnotexist');
        $this->assertCount(2, $this->node->getTags());
        $this->assertEquals(array('bar', 'foobar'), $this->node->getTags());
        $this->node->removeTag('bar');
        $this->assertCount(1, $this->node->getTags());
        $this->assertEquals(array('foobar'), $this->node->getTags());
        $this->node->removeTag('foobar');
        $this->assertEmpty($this->node->getTags());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::addConversationId
     * @covers \Cms\CoreBundle\Document\Node::getConversationIds
     */
    public function testAddConversationId()
    {
        $this->node->addConversationId('12345');
        $this->node->addConversationId('abcde');
        $this->node->addConversationId('zyx');
        $this->assertCount(3, $this->node->getConversationIds());
        $this->assertEquals(array('12345', 'abcde', 'zyx'), $this->node->getConversationIds());

        $this->node->addConversationId('12345');
        $this->assertCount(3, $this->node->getConversationIds());
        $this->node->addConversationId('zyx');
        $this->assertCount(3, $this->node->getConversationIds());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::removeConversationId
     * @covers \Cms\CoreBundle\Document\Node::addConversationId
     * @covers \Cms\CoreBundle\Document\Node::getConversationIds
     */
    public function testRemoveConversationId()
    {
        $this->node->addConversationId('12345');
        $this->node->addConversationId('abcde');
        $this->node->addConversationId('zyx');
        $this->assertCount(3, $this->node->getConversationIds());

        $this->node->removeConversationId('12345');
        $this->assertCount(2, $this->node->getConversationIds());
        $this->assertEquals(array('abcde', 'zyx'), $this->node->getConversationIds());
        $this->node->removeConversationId('idDoesNotExist');
        $this->assertCount(2, $this->node->getConversationIds());
        $this->node->removeConversationId('abcde');
        $this->assertCount(1, $this->node->getConversationIds());
        $this->assertEquals(array('zyx'), $this->node->getConversationIds());
        $this->node->removeConversationId('zyx');
        $this->assertEmpty($this->node->getConversationIds());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::addField
     * @covers \Cms\CoreBundle\Document\Node::getFields
     */
    public function testAddField()
    {
        $this->node->addField('shoeSize', 11);
        $this->node->addField('color', 'blue');
        $this->node->addField('town', 'truckee');
        $this->assertCount(3, $this->node->getFields());
        $this->assertEquals(array('shoeSize' => 11, 'color' => 'blue', 'town' => 'truckee'), $this->node->getFields());

        $this->node->addField('shoeSize', 11);
        $this->assertCount(3, $this->node->getFields());
        $this->node->addField('shoeSize', 9);
        $this->assertCount(3, $this->node->getFields());
        $this->assertEquals(array('shoeSize' => 9, 'color' => 'blue', 'town' => 'truckee'), $this->node->getFields());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::getField
     * @covers \Cms\CoreBundle\Document\Node::addField
     */
    public function testGetField()
    {
        $this->node->addField('shoeSize', 11);
        $this->node->addField('color', 'blue');
        $this->node->addField('town', 'truckee');
        $this->assertCount(3, $this->node->getFields());

        $this->assertNull($this->node->getField('width'));
        $this->assertEquals('blue', $this->node->getField('color'));
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::removeField
     * @covers \Cms\CoreBundle\Document\Node::addField
     * @covers \Cms\CoreBundle\Document\Node::getFields
     */
    public function testRemoveField()
    {
        $this->node->addField('shoeSize', 11);
        $this->node->addField('color', 'blue');
        $this->node->addField('town', 'truckee');
        $this->assertCount(3, $this->node->getFields());

        $this->node->removeField('shoeSize');
        $this->assertCount(2, $this->node->getFields());
        $this->assertEquals(array('color' => 'blue', 'town' => 'truckee'), $this->node->getFields());
        $this->node->removeField('thisKeyDoesNotExist');
        $this->assertCount(2, $this->node->getFields());
        $this->node->removeField('color');
        $this->assertCount(1, $this->node->getFields());
        $this->assertEquals(array('town' => 'truckee'), $this->node->getFields());
        $this->node->removeField('town');
        $this->assertEmpty($this->node->getFields());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::addView
     * @covers \Cms\CoreBundle\Document\Node::getView
     * @covers \Cms\CoreBundle\Document\Node::getViews
     */
    public function testAddView()
    {
        $this->node->addView('html', '<h1>hello world</h1>');
        $this->node->addView('json', '{"hello":"world"}');
        $this->node->addView('xml', '<hello world>');
        $this->assertCount(3, $this->node->getViews());
        $this->assertEquals(array('html' => '<h1>hello world</h1>', 'json' => '{"hello":"world"}', 'xml' => '<hello world>'), $this->node->getViews());

        $this->node->addView('html', '<h1>foobar</h1>');
        $this->assertCount(3, $this->node->getViews());
        $this->assertEquals('<h1>foobar</h1>', $this->node->getView('html'));
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::getView
     * @covers \Cms\CoreBundle\Document\Node::getViews
     * @covers \Cms\CoreBundle\Document\Node::addView
     */
    public function testGetView()
    {
        $this->node->addView('html', '<h1>hello world</h1>');
        $this->node->addView('json', '{"hello":"world"}');
        $this->node->addView('xml', '<hello world>');
        $this->assertCount(3, $this->node->getViews());

        $this->assertNull($this->node->getView('rss'));
        $this->assertEquals('<hello world>', $this->node->getView('xml'));
    }

}