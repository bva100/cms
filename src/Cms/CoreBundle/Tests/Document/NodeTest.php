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
     * @covers \Cms\CoreBundle\Document\Node::addMetadata
     * @covers \Cms\CoreBundle\Document\Node::removeMetadata
     * @covers \Cms\CoreBundle\Document\Node::getMetadata
     */
    public function testAddMetadataAndRemoveMetadata()
    {
        // initial set
        $this->node->addMetadata(array(
            'slug' => 'amazing-new-cms',
            'title' => 'New Cms Platform Review',
            'created' => time(),
            'template' => 'Summit',
            'tags' => array('cms', 'wordpress'),
            'site' => array('id' => '122gfhs', 'domain' => 'someDomain.com'),
            'categories' => array('parent' => 'review', 'sub' => 'platforms'),
            'author' => array('name' => 'Brian Anderson', 'url' => 'authmyapp.com', 'image' => 'http://wwww.someS3bucketUrl.com'),
        ));
        // override and add some data
        $this->node->addMetadata(array(
            'slug' => 'amazing-new-cms-platform',
            'tags' => array('drupal'),
            'categories' => array('parent' => 'tech', 'sub' => 'cms'),
        ));
        // get full metadata array
        $metadata = $this->node->getMetadata();
        $this->assertEquals('amazing-new-cms-platform', $metadata['slug']);
        $this->assertEquals('New Cms Platform Review', $metadata['title']);
        // runs tests with getters
        $this->assertEquals('amazing-new-cms-platform', $this->node->getMetadata('slug'));
        $this->assertEquals('New Cms Platform Review', $this->node->getMetadata('title'));
        $this->assertEquals('Summit', $this->node->getMetadata('template'));
        $this->assertContains('wordpress', $this->node->getMetadata('tags'));
        $this->assertContains('drupal', $this->node->getMetadata('tags'));
        $this->assertEquals(array('id' => '122gfhs', 'domain' => 'someDomain.com'), $this->node->getMetadata('site'));
        $this->assertContains( array('parent' => 'review', 'sub' => 'platforms'), $this->node->getMetadata('categories')  );
        $this->assertContains( array('parent' => 'tech', 'sub' => 'cms'), $this->node->getMetadata('categories'));
        $this->assertEquals( array('name' => 'Brian Anderson', 'url' => 'authmyapp.com', 'image' => 'http://wwww.someS3bucketUrl.com'), $this->node->getMetadata('author'));
        $this->assertInternalType('integer', $metadata['created']);

        // remove basic metadata
        $this->node->removeMetadata(array('metaType' => 'slug') );
        $this->assertNull($this->node->getMetadata('slug'));
        $this->node->removeMetadata(array('metaType' => 'title'));
        $this->assertNull($this->node->getMetadata('title'));
        $this->node->removeMetadata(array('metaType' => 'site'));
        $this->assertNull($this->node->getMetadata('site'));
        $this->node->removeMetadata(array('metaType' => 'author'));
        $this->assertNull($this->node->getMetadata('author'));

        // remove tag metadata
        $this->node->removeMetadata(array('metaType' => 'tags', 'pattern' => 'wordpress'));
        $this->assertEquals( array('cms', 'drupal'), $this->node->getMetadata('tags'));
        $this->node->removeMetadata( array('metaType' => 'tags', 'pattern' => 'drupal'));
        $this->assertEquals( array('cms'), $this->node->getMetadata('tags'));
        $this->node->removeMetadata( array('metaType' => 'tags', 'pattern' => 'cms'));
        $this->assertEmpty($this->node->getMetadata('tags'));
        $this->node->addMetadata(array('tags' => array('foo', 'bar', 'baz')));
        $this->assertCount(3, $this->node->getMetadata('tags'));
        $this->node->removeMetadata(array('metaType' => 'tags'));
        $this->assertEmpty($this->node->getMetadata('tags'));

    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::removeMetadata
     * @covers \Cms\CoreBundle\Document\Node::addMetadata
     * @covers \Cms\CoreBundle\Document\Node::getMetadata
     */
    public function testRemoveMetadataCategories()
    {
        $this->node->addMetadata(array('categories' => array('parent' => 'tech', 'sub' => 'cms')));
        $this->node->addMetadata(array('categories' => array('parent' => 'review', 'sub' => 'platform')));

        // remove categories metadata
        $this->node->removeMetadata(array('metaType' => 'categories', 'pattern' => array('parent' => 'tech', 'sub' => 'cms')));
        $this->assertCount(1, $this->node->getMetadata('categories'));

        // remove all with count 1
        $this->node->removeMetadata(array('metaType' => 'categories'));
        $this->assertEmpty($this->node->getMetadata('categories'));

        // remove all with empty
        $this->node->removeMetadata(array('metaType' => 'categories'));

        // remove all with count greater than 1
        $this->node->addMetadata(array('categories' => array('parent' => 'tech', 'sub' => 'cms')));
        $this->node->addMetadata(array('categories' => array('parent' => 'review', 'sub' => 'platform')));
        $this->node->addMetadata( array('categories' => array('parent' => 'foo', 'sub' => 'bar')) );
        $this->node->addMetadata( array('categories' => array('parent' => 'foo', 'sub' => 'baz')) );
        $this->node->addMetadata( array('categories' => array('parent' => 'foo')) );
        $this->node->removeMetadata(array('metaType' => 'categories'));
        $this->assertEmpty($this->node->getMetadata('catorgies'));

        // remove just parent category (all)
        $this->node->addMetadata( array('categories' => array('parent' => 'foo', 'sub' => 'bar')) );
        $this->node->addMetadata( array('categories' => array('parent' => 'foo', 'sub' => 'baz')) );
        $this->node->addMetadata( array('categories' => array('parent' => 'foo')) );
        $this->assertCount(3, $this->node->getMetadata('categories'));
        $this->node->removeMetadata(array('metaType' => 'categories', 'pattern' => array('parent' => 'foo')));
        $this->assertEmpty($this->node->getMetadata('categories'));

        // remove just parent category but ensure other parents still exist
        $this->node->addMetadata(array('categories' => array('parent' => 'tech', 'sub' => 'cms')));
        $this->node->addMetadata(array('categories' => array('parent' => 'review', 'sub' => 'platform')));
        $this->node->addMetadata( array('categories' => array('parent' => 'foo', 'sub' => 'bar')) );
        $this->node->addMetadata( array('categories' => array('parent' => 'foo', 'sub' => 'baz')) );
        $this->node->addMetadata( array('categories' => array('parent' => 'foo')) );
        $this->assertCount(5, $this->node->getMetadata('categories'));
        $this->node->removeMetadata(array('metaType' => 'categories', 'pattern' => array('parent' => 'foo')));
        $this->assertCount(2, $this->node->getMetadata('categories'));
        $this->assertContains( array('parent' => 'tech', 'sub' => 'cms'), $this->node->getMetadata('categories') );
        $this->assertContains( array('parent' => 'review', 'sub' => 'platform'), $this->node->getMetadata('categories') );
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::removeMetadata
     * @covers \Cms\CoreBundle\Document\Node::addMetadata
     * @covers \Cms\CoreBundle\Document\Node::getMetadata
     */
    public function testRemoveAllMetadata()
    {
        $this->node->addMetadata( array('title' => 'delete-me'));
        $this->node->addMetadata( array('tags' => array('foo', 'bar')) );
        $this->node->addMetadata( array('categories' => array('parent' => 'foo')) );
        $this->assertCount(3, $this->node->getMetadata());
        $this->node->removeAllMetadata();
        $this->assertEmpty($this->node->getMetadata());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::updateModifiedTimestamp
     * @covers \Cms\CoreBundle\Document\Node::getMetadata
     */
    public function testUpdateModifiedTimestamp()
    {
        $time = time();
        $this->node->updateModifiedTimestamp($time);
        $this->assertEquals($time, $this->node->getMetadata('modified'));
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::addView
     * @covers \Cms\CoreBundle\Document\Node::getView
     */
    public function testAddViewAndGetView()
    {
        $this->node->addView(array(
            'content' => '<h1>this is an error and should not appear</h1>',
            'json' => '{"hello" : "hello world"}',
        ));
        $this->node->addView(array('content' => '<h1>hello world</h1>'));
        $this->node->addView(array('xml' => 'this is XML'));
        $this->assertCount(3, $this->node->getView());
        $this->assertEquals(array('content' => '<h1>hello world</h1>', 'json' => '{"hello" : "hello world"}', 'xml' => 'this is XML'), $this->node->getView());
        $this->assertEquals('this is XML', $this->node->getView('xml'));
        $this->assertEquals('<h1>hello world</h1>', $this->node->getView('content'));

        $this->node->addView(array('xml' => null));
        $this->assertEquals('this is XML', $this->node->getView('xml'));
        $this->node->addView(array('xml' => 1));
        $this->assertEquals('this is XML', $this->node->getView('xml'));
        $this->node->addView(array('xml' => array('foo' => 'bar')));
        $this->assertEquals('this is XML', $this->node->getView('xml'));
        $this->node->addView(array('xml' => new \stdClass));
        $this->assertEquals('this is XML', $this->node->getView('xml'));

        $this->node->addView(array('content' => null));
        $this->assertEquals('<h1>hello world</h1>', $this->node->getView('content'));
        $this->node->addView(array('xml' => 1));
        $this->assertEquals('<h1>hello world</h1>', $this->node->getView('content'));
        $this->node->addView(array('content' => array('foo' => 'bar')));
        $this->assertEquals('<h1>hello world</h1>', $this->node->getView('content'));
        $this->node->addView(array('content' => new \stdClass));
        $this->assertEquals('<h1>hello world</h1>', $this->node->getView('content'));

        $this->node->addView(array('content' => null));
        $this->assertEquals('{"hello" : "hello world"}', $this->node->getView('json'));
        $this->node->addView(array('json' => 1));
        $this->assertEquals('{"hello" : "hello world"}', $this->node->getView('json'));
        $this->node->addView(array('json' => array('foo' => 'bar')));
        $this->assertEquals('{"hello" : "hello world"}', $this->node->getView('json'));
        $this->node->addView(array('json' => new \stdClass));
        $this->assertEquals('{"hello" : "hello world"}', $this->node->getView('json'));
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::removeView
     * @covers \Cms\CoreBundle\Document\Node::removeAllViews
     */
    public function testRemoveView()
    {
        $this->node->addView(array(
            'content' => '<h1>hello world</h1><p>this is some content</p>',
            'json' => '{"foo":"bar"}',
            'xml' => 'some xml',
        ));
        $this->assertCount(3, $this->node->getView());
        $this->node->removeView('content');
        $this->assertCount(2, $this->node->getView());
        $this->assertEquals(array('json' => '{"foo":"bar"}', 'xml' => 'some xml'), $this->node->getView());
        $this->node->removeView('json');
        $this->assertCount(1, $this->node->getView());
        $this->assertEquals(array('xml' => 'some xml'), $this->node->getView());
        $this->node->removeView('xml');
        $this->assertEmpty($this->node->getView());

        // remove all
        $this->node->addView(array(
            'content' => '<h1>hello world</h1><p>this is some content</p>',
            'json' => '{"foo":"bar"}',
            'xml' => 'some xml',
        ));
        $this->assertCount(3, $this->node->getView());
        $this->node->removeAllViews();
        $this->assertEmpty($this->node->getView());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Node::updateSlugPrefix
     * @covers \Cms\CoreBundle\Document\Node::addMetadata
     * @covers \Cms\CoreBundle\Document\Node::getMetadata
     */
    public function testSlugPrefixUpdate()
    {
        $this->node->addMetadata(array('slugPrefix' => 'review'));
        $this->node->addMetadata(array('slug' => 'cool-dog-toy'));
        $this->assertEquals('review/', $this->node->getMetadata('slugPrefix'));
        $this->assertEquals('review/cool-dog-toy', $this->node->getMetadata('slug'));
        $this->node->addMetadata(array('slugPrefix' => 'review/'));
        $this->assertEquals('review/', $this->node->getMetadata('slugPrefix'));
        $this->node->addMetadata(array('slug' => 'review/cool-dog-toy'));
        $this->assertEquals('review/cool-dog-toy', $this->node->getMetadata('slug'));

        $this->node->updateSlugPrefix('dog-toy');
        $this->assertEquals('dog-toy/', $this->node->getMetadata('slugPrefix'));
        $this->assertEquals('dog-toy/cool-dog-toy', $this->node->getMetadata('slug'));
    }

}