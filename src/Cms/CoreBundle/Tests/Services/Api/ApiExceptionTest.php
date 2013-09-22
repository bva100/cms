<?php
/**
 * User: Brian Anderson
 * Date: 9/12/13
 * Time: 7:44 PM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Services\Api\ApiException;

class ApiExceptionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::createHeaders
     */
    public function testCreateHeader()
    {
        $except = new ApiException(10001, 'json');
        $html = $except->createHeaders('html');
        $json = $except->createHeaders('json');
        $xml = $except->createHeaders('xml');
        $this->assertEquals(array('Content-Type' => 'text/html'), $html);
        $this->assertEquals(array('Content-Type' => 'application/json'), $json);
        $this->assertEquals(array('Content-Type' => 'application/xml'), $xml);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test10001Json()
    {
        $excep = new ApiException(10001, 'json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->errorCode, 10001);
        $this->assertEquals($data->meta->status, 403);
        $this->assertNotNull($data->meta->description);
        $this->assertNotNull($data->meta->message);
        $this->assertNotNull($data->meta->moreInfo);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test10001Xml()
    {
        $excep = new ApiException(10001, 'xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<errorCode>10001</errorCode>', $data);
        $this->assertContains('<status>403</status>', $data);
        $this->assertContains('<description>', $data);
        $this->assertContains('<message>', $data);
        $this->assertContains('<moreInfo>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test10002Json()
    {
        $excep = new ApiException(10002, 'json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->errorCode, 10002);
        $this->assertEquals($data->meta->status, 401);
        $this->assertNotNull($data->meta->description);
        $this->assertNotNull($data->meta->message);
        $this->assertNotNull($data->meta->moreInfo);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test10002Xml()
    {
        $excep = new ApiException(10002, 'xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<errorCode>10002</errorCode>', $data);
        $this->assertContains('<status>401</status>', $data);
        $this->assertContains('<description>', $data);
        $this->assertContains('<message>', $data);
        $this->assertContains('<moreInfo>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test10003Json()
    {
        $excep = new ApiException(10003, 'json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->errorCode, 10003);
        $this->assertEquals($data->meta->status, 404);
        $this->assertNotNull($data->meta->description);
        $this->assertNotNull($data->meta->message);
        $this->assertNotNull($data->meta->moreInfo);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test10003Xml()
    {
        $excep = new ApiException(10003, 'xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<errorCode>10003</errorCode>', $data);
        $this->assertContains('<status>404</status>', $data);
        $this->assertContains('<description>', $data);
        $this->assertContains('<message>', $data);
        $this->assertContains('<moreInfo>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test10004Json()
    {
        $excep = new ApiException(10004, 'json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->errorCode, 10004);
        $this->assertEquals($data->meta->status, 404);
        $this->assertNotNull($data->meta->description);
        $this->assertNotNull($data->meta->message);
        $this->assertNotNull($data->meta->moreInfo);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test10004Xml()
    {
        $excep = new ApiException(10004, 'xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<errorCode>10004</errorCode>', $data);
        $this->assertContains('<status>404</status>', $data);
        $this->assertContains('<description>', $data);
        $this->assertContains('<message>', $data);
        $this->assertContains('<moreInfo>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test20001Json()
    {
        $excep = new ApiException(20001, 'json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->errorCode, 20001);
        $this->assertEquals($data->meta->status, 404);
        $this->assertNotNull($data->meta->description);
        $this->assertNotNull($data->meta->message);
        $this->assertNotNull($data->meta->moreInfo);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test20001Xml()
    {
        $excep = new ApiException(20001, 'xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<errorCode>20001</errorCode>', $data);
        $this->assertContains('<status>404</status>', $data);
        $this->assertContains('<description>', $data);
        $this->assertContains('<message>', $data);
        $this->assertContains('<moreInfo>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test30003Json()
    {
        $excep = new ApiException(30003, 'json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->errorCode, 30003);
        $this->assertEquals($data->meta->status, 400);
        $this->assertNotNull($data->meta->description);
        $this->assertNotNull($data->meta->message);
        $this->assertNotNull($data->meta->moreInfo);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test30003Xml()
    {
        $excep = new ApiException(30003, 'xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<errorCode>30003</errorCode>', $data);
        $this->assertContains('<status>400</status>', $data);
        $this->assertContains('<description>', $data);
        $this->assertContains('<message>', $data);
        $this->assertContains('<moreInfo>', $data);
    }

}