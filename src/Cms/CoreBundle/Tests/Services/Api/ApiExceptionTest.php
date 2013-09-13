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
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test400Json()
    {
        $excep = new ApiException(400, 'application/json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->code, 400);
        $this->assertNotNull($data->meta->type);
        $this->assertNotNull($data->meta->moreInfo);
        $this->assertNotNull($data->meta->message);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test400Xml()
    {
        $excep = new ApiException(400, 'application/xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<code>400</code>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test400Html()
    {
        $excep = new ApiException(400, 'text/html');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('400', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test401Json()
    {
        $excep = new ApiException(401, 'application/json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->code, 401);
        $this->assertNotNull($data->meta->type);
        $this->assertNotNull($data->meta->moreInfo);
        $this->assertNotNull($data->meta->message);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test401Xml()
    {
        $excep = new ApiException(401, 'application/xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<code>401</code>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test401Html()
    {
        $excep = new ApiException(401, 'text/html');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('401', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test403Json()
    {
        $excep = new ApiException(403, 'application/json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->code, 403);
        $this->assertNotNull($data->meta->type);
        $this->assertNotNull($data->meta->moreInfo);
        $this->assertNotNull($data->meta->message);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test403Xml()
    {
        $excep = new ApiException(403, 'application/xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<code>403</code>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test403Html()
    {
        $excep = new ApiException(403, 'text/html');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('403', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test404Json()
    {
        $excep = new ApiException(404, 'application/json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->code, 404);
        $this->assertNotNull($data->meta->type);
        $this->assertNotNull($data->meta->moreInfo);
        $this->assertNotNull($data->meta->message);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test404Xml()
    {
        $excep = new ApiException(404, 'application/xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<code>404</code>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test404Html()
    {
        $excep = new ApiException(404, 'text/html');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('404', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test405Json()
    {
        $excep = new ApiException(405, 'application/json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->code, 405);
        $this->assertNotNull($data->meta->type);
        $this->assertNotNull($data->meta->moreInfo);
        $this->assertNotNull($data->meta->message);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test405Xml()
    {
        $excep = new ApiException(405, 'application/xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<code>405</code>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test405Html()
    {
        $excep = new ApiException(405, 'text/html');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('405', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test500Json()
    {
        $excep = new ApiException(500, 'application/json');
        $data = json_decode($excep->getMessage());
        $this->assertEquals($data->meta->code, 500);
        $this->assertNotNull($data->meta->type);
        $this->assertNotNull($data->meta->moreInfo);
        $this->assertNotNull($data->meta->message);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test500Xml()
    {
        $excep = new ApiException(500, 'application/xml');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('<code>500</code>', $data);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\ApiException::__construct
     * @covers Cms\CoreBundle\Services\Api\ApiException::output
     */
    public function test500Html()
    {
        $excep = new ApiException(500, 'text/html');
        $data = $excep->getMessage();
        $this->assertNotNull($data);
        $this->assertContains('500', $data);
    }
    
}