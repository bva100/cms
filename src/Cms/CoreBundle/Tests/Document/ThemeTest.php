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
        $this->assertCount(6, $this->theme->getLayouts());
        $this->assertEquals(array('Single', 'Loop', 'Static', 'foo', 'bar', 'foobar'), $this->theme->getLayouts());

        $this->theme->addLayout('foo');
        $this->assertCount(6, $this->theme->getLayouts());
        $this->theme->addLayout('bar');
        $this->assertCount(6, $this->theme->getLayouts());
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
        $this->assertCount(6, $this->theme->getLayouts());

        $this->theme->removeLayout('foo');
        $this->assertCount(5, $this->theme->getLayouts());
        $this->theme->removeLayout('foo');
        $this->assertCount(5, $this->theme->getLayouts());
        $this->assertEquals(array('Single', 'Loop', 'Static', 'bar', 'foobar'), $this->theme->getLayouts());
        $this->theme->removeLayout('bar');
        $this->assertCount(4, $this->theme->getLayouts());
        $this->assertEquals(array('Single', 'Loop', 'Static', 'foobar'), $this->theme->getLayouts());
        $this->theme->removeLayout('foobar')->removeLayout('Single')->removeLayout('Loop')->removeLayout('Static');
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

}