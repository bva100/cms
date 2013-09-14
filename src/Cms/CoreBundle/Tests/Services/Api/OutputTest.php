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
        $this->assertFalse($this->outputService->getForceCollection());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::checkResourceAndGetName
     * @covers Cms\CoreBundle\Services\Api\Output::setResources
     * @expectedException Cms\CoreBundle\Services\Api\ApiException
     */
    public function testResourceNotFound()
    {
        $this->outputService->setResources(array());
        $this->outputService->checkResourcesAndGetName();
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::checkResourceAndGetName
     * @covers Cms\CoreBundle\Services\Api\Output::setResources
     * @covers Cms\CoreBundle\Services\Api\Output::getResources
     * @covers Cms\CoreBundle\Services\Api\Output::setResourceName
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
     * @covers Cms\CoreBundle\Services\Api\Output::setResources
     * @covers Cms\CoreBundle\Services\Api\Output::getResources
     * @covers Cms\CoreBundle\Services\Api\Output::setResourceName
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
     * @covers Cms\CoreBundle\Services\Api\Output::checkResourceAndGetName
     * @covers Cms\CoreBundle\Services\Api\Output::setResources
     * @covers Cms\CoreBundle\Services\Api\Output::getResources
     * @covers Cms\CoreBundle\Services\Api\Output::setResourceName
     * @covers Cms\CoreBundle\Services\Api\Output::forceCollection
     */
    public function testForceCollectOnSingleResource()
    {
        $resourceObj = new stdClass;
        $resourceObj->id = 'foobar';
        $resourceObj->title = 'foo and bar';
        $resource = array($resourceObj);

        $this->outputService->setResourceNames(array('singular' => 'resource', 'plural' => 'resources'));
        $this->outputService->setResources($resource);
        $this->outputService->setForceCollection(true);
        $this->assertEquals($this->outputService->checkResourcesAndGetName(), 'resources');
        $this->assertEquals($resource, $this->outputService->getResources());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::output
     * @covers Cms\CoreBundle\Services\Api\Output::setResources
     * @covers Cms\CoreBundle\Services\Api\Output::setResourceName
     * @covers Cms\CoreBundle\Services\Api\Output::setMeta
     * @covers Cms\CoreBundle\Services\Api\Output::setNotifications
     */
    public function testOutputJsonSingle()
    {
        $resource = new stdClass;
        $resource->id = 'foobar';
        $resource->title = 'foo and bar';
        $resourceArray = array($resource);
        $meta = array('code' => 200, 'offset' => 0, 'limit' => 10);
        $notifications = array('updates' => 'This is an update notification.');
        $expected = json_encode(array(
            'resource' => $resource,
            'meta' => $meta,
            'notifications' => $notifications,
        ));

        $this->outputService->setResources($resourceArray)->setResourceNames(array('singular' => 'resource', 'plural' => 'resources'))->setFormat('json')->setMeta($meta)->setNotifications($notifications);
        $output = $this->outputService->output();
        $this->assertContains('HTTP/1.0 200 OK', (string)$output);
        $this->assertContains($expected, (string)$output);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Output::output
     * @covers Cms\CoreBundle\Services\Api\Output::setResources
     * @covers Cms\CoreBundle\Services\Api\Output::setResourceName
     * @covers Cms\CoreBundle\Services\Api\Output::setMeta
     * @covers Cms\CoreBundle\Services\Api\Output::setNotifications
     */
    public function testOutputJsonCollection()
    {
        $resourceObj1 = new stdClass;
        $resourceObj1->id = 'foobar';
        $resourceObj1->title = 'foo and bar';
        $resourceObj2 = new stdClass;
        $resourceObj2->id = 'foobar two';
        $resourceObj2->title = 'foo two and bar two';
        $resources = array($resourceObj1, $resourceObj2);
        $meta = array('code' => 200, 'offset' => 0, 'limit' => 10);
        $notifications = array('updates' => 'This is an update notification.');
        $expected = json_encode(array(
            'resources' => $resources,
            'meta' => $meta,
            'notifications' => $notifications,
        ));

        $this->outputService->setResources($resources)->setResourceNames(array('singular' => 'resource', 'plural' => 'resources'))->setFormat('json')->setMeta($meta)->setNotifications($notifications);
        $output = $this->outputService->output();
        $this->assertContains('HTTP/1.0 200 OK', (string)$output);
        $this->assertContains($expected, (string)$output);
    }

    public function testOuputJsonForceCollectionOnSingleResults()
    {
        $resource = new stdClass;
        $resource->id = 'foobar';
        $resource->title = 'foo and bar';
        $resourceArray = array($resource);
        $meta = array('code' => 200, 'offset' => 0, 'limit' => 10);
        $notifications = array('updates' => 'This is an update notification.');
        $expected = json_encode(array(
            'resources' => $resourceArray,
            'meta' => $meta,
            'notifications' => $notifications,
        ));

        $this->outputService->setResources($resourceArray)->setResourceNames(array('singular' => 'resource', 'plural' => 'resources'))->setFormat('json')->setForceCollection(true)->setMeta($meta)->setNotifications($notifications);
        $output = $this->outputService->output();
        $this->assertContains('HTTP/1.0 200 OK', (string)$output);
        $this->assertContains($expected, (string)$output);
    }

//    /**
//     * @covers Cms\CoreBundle\Services\Api\Output::output
//     * @covers Cms\CoreBundle\Services\Api\Output::setResources
//     * @covers Cms\CoreBundle\Services\Api\Output::setResourceName
//     * @covers Cms\CoreBundle\Services\Api\Output::setMeta
//     * @covers Cms\CoreBundle\Services\Api\Output::setNotifications
//     */
//    public function testOutputXmlSingle()
//    {
//        $resource = new stdClass;
//        $resource->id = 'foobar';
//        $resource->title = 'foo and bar';
//        $resourceArray = array($resource);
//        $meta = array('code' => 200, 'offset' => 0, 'limit' => 10);
//        $notifications = array('updates' => 'This is an update notification.');
//        $expected = json_encode(array(
//            'resource' => $resource,
//            'meta' => $meta,
//            'notifications' => $notifications,
//        ));
//
//        $this->outputService->setResources($resourceArray)->setResourceNames(array('singular' => 'resource', 'plural' => 'resources'))->setFormat('xml')->setMeta($meta)->setNotifications($notifications);
//        $output = $this->outputService->output();
//        $this->assertContains('HTTP/1.0 200 OK', (string)$output);
//        $this->assertContains($expected, (string)$output);
//    }

}