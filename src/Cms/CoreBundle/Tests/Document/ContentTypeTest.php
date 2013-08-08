<?php
/**
 * User: Brian Anderson
 * Date: 6/8/13
 * Time: 6:37 PM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\ContentType;

/**
 * Class ContentTypeTest
 * @package Cms\CoreBundle\Tests
 */
class ContentTypeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cms\CoreBundle\Document\ContentType
     */
    private $contentType;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->contentType = new ContentType();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->contentType = new ContentType();
    }

    /**
     * @covers \Cms\CoreBundle\Document\ContentType::addFormat
     * @covers \Cms\CoreBundle\Document\ContentType::getFormats
     */
    public function testAddFormat()
    {
        $this->contentType->addFormat('static');
        $this->contentType->addFormat('single');
        $this->contentType->addFormat('loop');
        $this->assertCount(3, $this->contentType->getFormats());
        $this->assertEquals(array('static', 'single', 'loop'), $this->contentType->getFormats());

        $this->contentType->addFormat('static');
        $this->assertCount(3, $this->contentType->getFormats());
        $this->contentType->addFormat('single');
        $this->assertEquals(array('static', 'single', 'loop'), $this->contentType->getFormats());
    }

    /**
     * @covers \Cms\CoreBundle\Document\ContentType::removeFormat
     * @covers \Cms\CoreBundle\Document\ContentType::addFormat
     * @covers \Cms\CoreBundle\Document\ContentType::getFormats
     */
    public function testRemoveFormat()
    {
        $this->contentType->addFormat('static');
        $this->contentType->addFormat('single');
        $this->contentType->addFormat('loop');
        $this->assertCount(3, $this->contentType->getFormats());

        $this->contentType->removeFormat('static');
        $this->assertCount(2, $this->contentType->getFormats());
        $this->assertEquals(array('single', 'loop'), $this->contentType->getFormats());
        $this->contentType->removeFormat('static');
        $this->assertCount(2, $this->contentType->getFormats());
        $this->contentType->removeFormat('single');
        $this->assertCount(1, $this->contentType->getFormats());
        $this->assertEquals(array('loop'), $this->contentType->getFormats());
        $this->contentType->removeFormat('loop');
        $this->assertEmpty($this->contentType->getFormats());
    }

    /**
     * @covers \Cms\CoreBundle\Document\ContentType::addCategory
     * @covers \Cms\CoreBundle\Document\ContentType::getCategories
     * @covers \Cms\CoreBundle\Document\ContentType::removeCategory
     */
    public function testAddCategory()
    {
        $this->contentType->addCategory('foo', 'bar');
        $categories = $this->contentType->getCategories();
        $this->assertCount(1, $categories);
        $this->assertEquals(array('parent' => 'foo', 'sub' => 'bar'), $categories[0]);

        $this->contentType->addCategory('foo', 'bar');
        $categories = $this->contentType->getCategories();
        $this->assertCount(1, $this->contentType->getCategories());
        $this->assertEquals(array('parent' => 'foo', 'sub' => 'bar'), $categories[0]);

        $this->contentType->addCategory('hello, world', 'PHP is cool');
        $this->assertCount(2, $this->contentType->getCategories());
        $this->assertEquals(array(0 => array('parent' => 'foo', 'sub' => 'bar'), 1 => array('parent' => 'hello, world', 'sub' => 'php is cool') ), $this->contentType->getCategories());

        $this->contentType->addCategory('dog');
        $this->assertCount(3, $this->contentType->getCategories());

        $this->contentType->addCategory('dog');
        $this->assertCount(3, $this->contentType->getCategories());

        $this->contentType->addCategory('dog', 'toys');
        $this->assertCount(4, $this->contentType->getCategories());

        $this->contentType->addCategory(1);
        $this->assertCount(4, $this->contentType->getCategories());
        $this->contentType->addCategory(array());
        $this->assertCount(4, $this->contentType->getCategories());
        $this->contentType->addCategory(new \stdClass(), 'foobar');
        $this->assertCount(4, $this->contentType->getCategories());
        $this->contentType->addCategory(null);
        $this->assertCount(4, $this->contentType->getCategories());
    }

    /**
     * @covers \Cms\CoreBundle\Document\ContentType::addCategory
     * @covers \Cms\CoreBundle\Document\ContentType::getCategories
     */
    public function testAddCategoryForceParent()
    {
        $this->contentType->addCategory('foo', 'bar', true);
        $categories = $this->contentType->getCategories();
        $this->assertCount(2, $categories);
        $this->assertContains(array('parent' => 'foo', 'sub' => 'bar'), $categories);
        $this->assertContains(array('parent' => 'foo'), $categories);

        $this->contentType->addCategory('foo', 'bar', true);
        $this->assertCount(2, $this->contentType->getCategories());
        $this->contentType->addCategory('foo');
        $this->assertCount(2, $this->contentType->getCategories());
        $this->contentType->addCategory('foo', null, false);
        $this->assertCount(2, $this->contentType->getCategories());
        $this->contentType->addCategory('foo', 'baz', true);
        $this->assertCount(3, $this->contentType->getCategories());
        $this->assertEquals(array(array('parent' => 'foo'), array('parent' => 'foo', 'sub' => 'bar'), array('parent' => 'foo', 'sub' => 'baz')), $this->contentType->getCategories());
        $this->contentType->addCategory('baz', 'boom', true);
        $this->assertCount(5, $this->contentType->getCategories());

    }

    /**
     * @covers \Cms\CoreBundle\Document\ContentType::removeCategory
     * @covers \Cms\CoreBundle\Document\ContentType::getCategories
     * @covers \Cms\CoreBundle\Document\ContentType::addCategory
     */
    public function testRemoveCategory()
    {
        $this->contentType->addCategory('foo');
        $this->contentType->addCategory('hello', 'world');
        $this->contentType->addCategory('dog', 'toys');
        $this->assertCount(3, $this->contentType->getCategories());

        $this->contentType->removeCategory('foo');
        $this->assertCount(2, $this->contentType->getCategories());
        $this->contentType->removeCategory('hello', 'world');
        $this->assertCount(1, $this->contentType->getCategories());

        $this->contentType->removeCategory('hello');
        $this->assertCount(1, $this->contentType->getCategories());

        $this->contentType->removeCategory('hello', 'world');
        $this->assertCount(1, $this->contentType->getCategories());

        $this->contentType->removeCategory('dog', 'toys');
        $this->assertEmpty($this->contentType->getCategories());
    }

    /**
     * @covers \Cms\CoreBundle\Document\ContentType::addField
     * @covers \Cms\CoreBundle\Document\ContentType::removeField
     */
    public function testAddField()
    {
        $this->contentType->addField('foo');
        $this->contentType->addField('bar');
        $this->contentType->addField('foobar');
        $this->assertCount(3, $this->contentType->getFields());
        $this->assertEquals(array('foo', 'bar', 'foobar'), $this->contentType->getFields());

        $this->contentType->addField('foo');
        $this->assertCount(3, $this->contentType->getFields());
        $this->contentType->addField('foobar');
        $this->assertCount(3, $this->contentType->getFields());
    }

    /**
     * @covers \Cms\CoreBundle\Document\ContentType::addField
     * @covers \Cms\CoreBundle\Document\ContentType::removeField
     */
    public function testRemoveField()
    {
        $this->contentType->addField('foo');
        $this->contentType->addField('bar');
        $this->contentType->addField('foobar');
        $this->assertCount(3, $this->contentType->getFields());

        $this->contentType->removeField('foo');
        $this->assertCount(2, $this->contentType->getFields());
        $this->assertEquals(array('bar', 'foobar'), $this->contentType->getFields());
        $this->contentType->removeField('foo');
        $this->contentType->removeField('bar');
        $this->assertCount(1, $this->contentType->getFields());
        $this->assertEquals(array('foobar'), $this->contentType->getFields());
        $this->contentType->removeField('foobar');
        $this->assertEmpty($this->contentType->getFields());
    }

    /**
     * @covers \Cms\CoreBundle\Document\ContentType::addLoopSlug
     * @covers \Cms\CoreBundle\Document\ContentType::removeLoopSlug
     */
    public function testAddRemoveLoopSlugs()
    {
        $this->contentType->addLoopSlug('foo');
        $this->contentType->addLoopSlug('bar');
        $this->contentType->addLoopSlug('foobar');
        $this->assertCount(3, $this->contentType->getLoopSlugs());
        $this->assertEquals(array('foo', 'bar', 'foobar'), $this->contentType->getLoopSlugs());

        $this->contentType->addLoopSlug('foo');
        $this->assertCount(3, $this->contentType->getLoopSlugs());

        $this->contentType->removeLoopSlug('foobar');
        $this->assertCount(2, $this->contentType->getLoopSlugs());
        $this->assertEquals(array('foo', 'bar'), $this->contentType->getLoopSlugs());
        $this->contentType->removeLoopSlug('foobar');
        $this->assertCount(2, $this->contentType->getLoopSlugs());
        $this->contentType->removeLoopSlug('foo');
        $this->assertCount(1, $this->contentType->getLoopSlugs());
        $this->contentType->removeLoopSlug('bar');
        $this->assertEmpty($this->contentType->getLoopSlugs());
    }

}