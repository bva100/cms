<?php
/**
 * User: Brian Anderson
 * Date: 9/13/13
 * Time: 11:06 AM
 */

namespace Cms\CoreBundle\Tests;

use PHPUnit_Framework_TestCase as PhpUnit;
use Cms\CoreBundle\Services\Api\Output;
use stdClass;

class OutputTest extends PhpUnit {

    private $outputService;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->outputService = new Output();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->outputService = new Output();
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::__construct
     * @covers Cms\CoreBundle\Services\Api\Output::setMeta
     * @covers Cms\CoreBundle\Services\Api\Output::getMeta
     * @covers Cms\CoreBundle\Services\Api\Output::setNotifications
     * @covers Cms\CoreBundle\Services\Api\Output::getNotifications
     */
    public function testConstructGetMetaAndGetNotifications()
    {
        $this->assertEquals($this->outputService->getMeta(), array('code' => 200));
        $this->assertEquals($this->outputService->getNotifications(), array());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::checkResourceAndGetName
     * @expectedException Cms\CoreBundle\Services\Api\ApiException
     */
    public function testResourceNotFound()
    {
        $this->outputService->setResources(array());
        $this->outputService->checkResourcesAndGetName();
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::checkResourceAndGetName
     */
    public function testSingleResourceName()
    {
        $resourceObj = new stdClass;
        $resourceObj->id = 'foobar';
        $resourceObj->title = 'foo and bar';
        $resource = array($resourceObj);

        $this->outputService->setResourceNames(array('singular' => 'resource', 'plural' => 'resources'));
        $this->outputService->setResources($resource);
        $resourceName = $this->outputService->checkResourcesAndGetName();
        $this->assertEquals($resourceName, 'resource');
        $this->assertEquals($this->outputService->getResources(), $resourceObj);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::checkResourceAndGetName
     */
    public function testPluralResourceName()
    {
        $resourceObj1 = new stdClass;
        $resourceObj1->id = 'foobar';
        $resourceObj1->title = 'foo and bar';
        $resourceObj2 = new stdClass;
        $resourceObj2->id = 'foobar two';
        $resourceObj2->title = 'foo two and bar two';
        $resources = array($resourceObj1, $resourceObj2);

        $this->outputService->setResourceNames(array('singular' => 'resource', 'plural' => 'resources'));
        $this->outputService->setResources($resources);
        $resourceName = $this->outputService->checkResourcesAndGetName();
        $this->assertEquals($resourceName, 'resources');
        $this->assertEquals($resources, $this->outputService->getResources());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::output
     */
    public function testOutputJson()
    {
        $resourceObj1 = new stdClass;
        $resourceObj1->id = 'foobar';
        $resourceObj1->title = 'foo and bar';
        $resourceObj2 = new stdClass;
        $resourceObj2->id = 'foobar two';
        $resourceObj2->title = 'foo two and bar two';
        $resources = array($resourceObj1, $resourceObj2);
        $meta = array('code' => 200, 'offset' => 0, 'limit' => 10);
        $notifications = array('updates' => 'breaking changes to this endpoint are coming soon!');
        $expected = json_encode(array(
            'resources' => $resources,
            'meta' => $meta,
            'notifications' => $notifications,
        ));
        
        $this->outputService->setResources($resources)->setResourceNames(array('singular' => 'resource', 'plural' => 'resources'))->setMeta($meta)->setNotifications($notifications);
        $output = $this->outputService->output();
        $this->assertContains('HTTP/1.0 200 OK', (string)$output);
        $this->assertContains($expected, (string)$output);
    }

}