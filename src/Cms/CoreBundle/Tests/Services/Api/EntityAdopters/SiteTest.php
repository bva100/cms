<?php


namespace Cms\CoreBundle\Tests\EntityAdopters;

use Cms\CoreBundle\Document\Site;
use Cms\CoreBundle\Services\Api\EntityAdopters\SiteAdopter;

class SiteTest extends \PHPUnit_Framework_TestCase {

    private $adopter;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->adopter = new SiteAdopter();
    }

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->adopter = new SiteAdopter();
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\EntityAdopters\SiteAdopter::SetResource
     */
    public function testSetResourceAndGetResource()
    {
        $this->adopter->setResource(new Site());
        $this->assertInstanceOf('Cms\CoreBundle\Document\Site', $this->adopter->getResource());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\EntityAdopters\SiteAdopter::convert
     */
    public function testConvert()
    {
        $name = 'foo';
        $namespace = 'foobar';
        $domains = array('somefoo.com', 'somebar.com');
        $created = time();

        $site = new Site();
        $site->setName($name);
        $site->setNamespace($namespace);
        $site->setDomains($domains);
        $site->setCreated($created);

        $this->adopter->setResource($site);
        $obj = $this->adopter->convert();
        $this->assertEquals($name, $obj->name);
        $this->assertEquals($namespace, $obj->namespace);
        $this->assertEquals($domains, $obj->domains);
        $this->assertEquals($created, $obj->created);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\EntityAdopters\SiteAdopter::getFromArray
     */
    public function testGetFromArray()
    {
        $site = new Site();
        $site->setName('foo');
        $site->setNamespace('bar');
        $site->setDomains(array('fluffy', 'foo'));

        $params = array(
            'name' => 'boom',
            'namespace' => 'bigBoom',
            'domains' => array('milkywaysandfoobars.com', 'bingbangfoo.com'),
        );
        $this->adopter->setResource($site);
        $newSite = $this->adopter->getFromArray($params);
        $this->assertEquals($params['name'], $newSite->getName());
        $this->assertEquals($params['namespace'], $newSite->getNamespace());
        $this->assertEquals($params['domains'], $newSite->getDomains());
    }

}