<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 11:53 AM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\Group;
use Cms\CoreBundle\Document\Site;

/**
 * Class SiteTest
 * @package Cms\CoreBundle\Tests
 */
class SiteTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cms\CoreBundle\Document\Site
     */
    private $site;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->site = new Site();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->site = new Site();
    }

    /**
     * @covers \Cms\CoreBundle\Document\Site::addDomain
     * @covers \Cms\CoreBundle\Document\Site::getDomains
     */
    public function testAddDomain()
    {
        $this->site->addDomain('domain.com');
        $this->site->addDomain('domain.uk');
        $this->site->addDomain('foobar.co.es');
        $this->assertCount(3, $this->site->getDomains());
        $this->assertEquals(array('domain.com', 'domain.uk', 'foobar.co.es'), $this->site->getDomains());

        $this->site->addDomain('domain.com');
        $this->assertCount(3, $this->site->getDomains());
        $this->assertEquals(array('domain.com', 'domain.uk', 'foobar.co.es'), $this->site->getDomains());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Site::removeDomain
     * @covers \Cms\CoreBundle\Document\Site::addDomain
     * @covers \Cms\CoreBundle\Document\Site::getDomains
     */
    public function testRemoveDomain()
    {
        $this->site->addDomain('domain.com');
        $this->site->addDomain('domain.uk');
        $this->site->addDomain('foobar.co.es');
        $this->assertCount(3, $this->site->getDomains());

        $this->site->removeDomain('domain.com');
        $this->assertCount(2, $this->site->getDomains());
        $this->site->removeDomain('domain.com');
        $this->assertCount(2, $this->site->getDomains());
        $this->assertEquals(array('domain.uk', 'foobar.co.es'), $this->site->getDomains());
        $this->site->removeDomain('domain.uk');
        $this->assertCount(1, $this->site->getDomains());
        $this->site->removeDomain('foobar.co.es');
        $this->assertEmpty($this->site->getDomains());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Site::addTemplateName
     * @covers \Cms\CoreBundle\Document\Site::removeTemplateName
     * @covers \Cms\CoreBundle\Document\Site::getTemplateNames
     */
    public function testAddTemplateName()
    {
        $this->site->addTemplateName('Core:Base:HTML');
        $this->site->addTemplateName('bar');
        $this->site->addTemplateName('foo');
        $this->assertCount(3, $this->site->getTemplateNames());
        $this->assertEquals(array('Core:Base:HTML', 'bar', 'foo'), $this->site->getTemplateNames());

        $this->site->addTemplateName('Core:Base:HTML');
        $this->assertCount(3, $this->site->getTemplateNames());
        $this->site->addTemplateName('bar');
        $this->assertCount(3, $this->site->getTemplateNames());

        $this->site->removeTemplateName('Core:Base:HTML');
        $this->assertCount(2, $this->site->getTemplateNames());
        $this->assertEquals(array('bar', 'foo'), $this->site->getTemplateNames());
        $this->site->removeTemplateName('bar');
        $this->assertCount(1, $this->site->getTemplateNames());
        $this->site->removeTemplateName('foo');
        $this->assertEmpty($this->site->getTemplateNames());

        $this->site->setTemplateNames(array('boom', 'bang'));
        $this->assertCount(2, $this->site->getTemplateNames());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Site::hasTemplateName
     */
    public function testHasTemplateName()
    {
        $this->site->setTemplateNames(array('foobar', 'baz'));
        $this->site->addTemplateName('foo');
        $this->assertCount(3, $this->site->getTemplateNames());

        $this->assertTrue($this->site->hasTemplateName('foobar'));
        $this->assertFalse($this->site->hasTemplateName('bar'));
        $this->assertTrue($this->site->hasTemplateName('foo'));
        $this->assertFalse($this->site->hasTemplateName(1));
    }

    /**
     * @covers \Cms\CoreBundle\Document\Site::setThemes
     * @covers \Cms\CoreBundle\Document\Site::addTheme
     * @covers \Cms\CoreBundle\Document\Site::removeTheme
     * @covers \Cms\CoreBundle\Document\Site::removeThemeById
     * @covers \Cms\CoreBundle\Document\Site::getThemes
     */
    public function testAddTheme()
    {
        $this->site->setThemes(array());
        $this->assertEmpty($this->site->getThemes());
        $firstTheme = array('id' => '1', 'name' => 'firstTheme', 'description' => 'first theme and a foobar', 'image' => 'firsththeme.com/pic.png');
        $secondTheme = array('id' => '2', 'name' => 'secondTheme', 'description' => 'second theme and a baz', 'image' => 'secondtheme.com/image.jpg');
        $thirdTheme = array('id' => '3', 'name' => 'thirdTheme', 'description' => 'third theme and a boom', 'image' => 'thirdtheme.com/gallery-one.png');
        $themes = array($firstTheme, $secondTheme, $thirdTheme);
        $this->site->setThemes($themes);
        $this->assertCount(3, $this->site->getThemes());
        $this->assertEquals($themes, $this->site->getThemes());
        $this->site->setThemes(array());
        $this->assertEmpty($this->site->getThemes());

        $this->site->addTheme($firstTheme);
        $this->assertEquals(array($firstTheme), $this->site->getThemes());
        $this->site->addTheme($secondTheme);
        $this->assertCount(2, $this->site->getThemes());
        $this->assertEquals(array($firstTheme, $secondTheme), $this->site->getThemes());
        $this->site->removeTheme($secondTheme);
        $this->assertEquals(array($firstTheme), $this->site->getThemes());
        $this->site->removeTheme($firstTheme);
        $this->assertEmpty($this->site->getThemes());

        $this->site->setThemes($themes);
        $this->assertCount(3, $this->site->getThemes());
        $this->site->removeThemeById('1');
        $this->assertCount(2, $this->site->getThemes());
        $this->assertEquals(array($secondTheme, $thirdTheme), $this->site->getThemes());
        $this->site->removeThemeById('2');
        $this->assertCount(1, $this->site->getThemes());
        $this->site->removeThemeById('3');
        $this->assertEmpty($this->site->getThemes());
    }

    /**
     * @covers Cms\CoreBundle\Tests::addGroup
     * @covers Cms\CoreBundle\Tests::getGroups
     */
    public function testAddGroupAndRemoveGroup()
    {
        $group = new Group();
        $this->site->addGroup($group);
        $this->assertCount(1, $this->site->getGroups());

        $group2 = new Group();
        $this->site->addGroup($group2);
        $this->assertCount(2, $this->site->getGroups());
    }

    /**
     * @covers Cms\CoreBundle\Tests::addGroup
     * @covers Cms\CoreBundle\Tests::getGroups
     * @covers Cms\CoreBundle\Tests::getGroupById
     */
    public function testGetGroupById()
    {
        $group = new Group();
        $group->setId('1234');
        $this->site->addGroup($group);
        $this->assertCount(1, $this->site->getGroups());
        $this->assertEquals($group, $this->site->getGroup($group->getId()));

        $group2 = new Group();
        $group->setid('876');
        $this->site->addGroup($group2);
        $this->assertCount(2, $this->site->getGroups());
        $this->assertEquals($group2, $this->site->getGroup($group2->getId()));
    }

    /**
     * @covers Cms\CoreBundle\Tests::addGroup
     * @covers Cms\CoreBundle\Tests::getGroups
     * @covers Cms\CoreBundle\Tests::getGroupByName
     */
    public function testGetGroupByName()
    {
        $group = new Group();
        $group->setName('foobar');
        $this->site->addGroup($group);
        $this->assertCount(1, $this->site->getGroups());
        $this->assertEquals($group, $this->site->getGroupByName($group->getName()));

        $group2 = new Group();
        $group->setName('bar');
        $this->site->addGroup($group2);
        $this->assertCount(2, $this->site->getGroups());
        $this->assertEquals($group2, $this->site->getGroupByName($group2->getName()));
    }

}