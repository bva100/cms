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


}