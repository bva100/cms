<?php
/**
 * User: Brian Anderson
 * Date: 6/21/13
 * Time: 4:27 PM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\Theme;


/**
 * Class ThemeTest
 * @package Cms\CoreBundle\Tests
 */
class ThemeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cms\CoreBundle\Document\Theme
     */
    private $theme;

    /**
     * @coversNothings
     */
    public function __construct()
    {
        $this->theme = new Theme();
    }

    /**
     * @coversNothings
     */
    public function setUp()
    {
        $this->theme = new Theme();
    }

    /**
     * @covers \Cms\CoreBundle\Document\Theme::addLayout
     * @covers \Cms\CoreBundle\Document\Theme::getLayouts
     */
    public function testAddLayout()
    {
        $this->theme->addLayout('foo');
        $this->theme->addLayout('bar');
        $this->theme->addLayout('foobar');
        $this->assertCount(3, $this->theme->getLayouts());
        $this->assertEquals(array('foo', 'bar', 'foobar'), $this->theme->getLayouts());

        $this->theme->addLayout('foo');
        $this->assertCount(3, $this->theme->getLayouts());
        $this->theme->addLayout('bar');
        $this->assertCount(3, $this->theme->getLayouts());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Theme::removeLayout
     * @covers \Cms\CoreBundle\Document\Theme::addLayout
     * @covers \Cms\CoreBundle\Document\Theme::getLayouts
     */
    public function testRemoveLayout()
    {
        $this->theme->addLayout('foo');
        $this->theme->addLayout('bar');
        $this->theme->addLayout('foobar');
        $this->assertCount(3, $this->theme->getLayouts());

        $this->theme->removeLayout('foo');
        $this->assertCount(2, $this->theme->getLayouts());
        $this->theme->removeLayout('foo');
        $this->assertCount(2, $this->theme->getLayouts());
        $this->assertEquals(array('bar', 'foobar'), $this->theme->getLayouts());
        $this->theme->removeLayout('bar');
        $this->assertCount(1, $this->theme->getLayouts());
        $this->assertEquals(array('foobar'), $this->theme->getLayouts());
        $this->theme->removeLayout('foobar');
        $this->assertEmpty($this->theme->getLayouts());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Theme::hasLayout
     * @covers \Cms\CoreBundle\Document\Theme::addLayout
     */
    public function testHasLayout()
    {
        $this->theme->addLayout('foo');
        $this->theme->addLayout('bar');
        $this->theme->addLayout('foobar');
        $this->assertTrue($this->theme->hasLayout('foo'));
        $this->assertTrue($this->theme->hasLayout('bar'));
        $this->assertFalse($this->theme->hasLayout('baz'));
        $this->assertFalse($this->theme->hasLayout(1));
    }

    /**
     * @covers \Cms\CoreBundle\Document\Theme::addAuthor
     * @covers \Cms\CoreBundle\Document\Theme::removeAuthor
     * @covers \Cms\CoreBundle\Document\Theme::getAuthor
     */
    public function testSetAuthor()
    {
        $this->theme->addAuthor(array('name' => 'foo', 'url' => 'foobar.com', 'image' => 'foobar.com/image.png'));
        $this->assertEquals(array('name' => 'foo', 'url' => 'foobar.com', 'image' => 'foobar.com/image.png' ), $this->theme->getAuthor());
        $this->theme->addAuthor(array('name' => 'baz', 'url' => 'bazboom.com'));
        $this->assertEquals(array('name' => 'baz', 'url' => 'bazboom.com'), $this->theme->getAuthor());
        $this->theme->removeAuthor();
        $this->assertEmpty($this->theme->getAuthor());
    }

}