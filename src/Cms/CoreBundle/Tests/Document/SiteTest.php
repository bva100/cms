<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 11:53 AM
 */

namespace Cms\CoreBundle\Tests;

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
}