<?php
/**
 * User: Brian Anderson
 * Date: 9/17/13
 * Time: 10:44 PM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Services\Api\Base;

class BaseTest extends \PHPUnit_Framework_TestCase {

    private $base;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->base = new Base();
    }

    /**
     * @coversNothing
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function createOptions($offset = 0, $limit = 10)
    {
        return array('offset' => $offset, 'limit' => $limit);
    }

    /**
     * Set Server params. Sets some sort of sensible defaults
     *
     * @param string $https
     * @param string $host
     * @param string $uri
     * @param string $queryString
     */
    public function setServerParams($https = 'off', $host = 'pipestack.com', $uri = '/api/v1/nodes', $queryString = 'search=bacon')
    {
        $_SERVER['HTTPS'] = $https;
        $_SERVER['HTTP_HOST'] = $host;
        $_SERVER['DOCUMENT_URI'] = $uri;
        $_SERVER['QUERY_STRING'] = $queryString;
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Base::getBaseUrl
     */
    public function testGetBaseUrl()
    {
        $this->setServerParams();
        $base = new Base();
        $this->assertEquals('http://pipestack.com/api/v1', $base->getBaseApiUrl());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Base::getCurrentUrl
     */
    public function testGetCurrentUrl()
    {
        $this->setServerParams();
        $base = new Base();
        $this->assertEquals('http://pipestack.com/api/v1/nodes?search=bacon', $base->getCurrentUrl());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Base::getNextUrl
     */
    public function testGetNextUrl()
    {
        $this->setServerParams('on', 'foobar.com', '/api/v1/nodes', 'limit=10&offset=10');
        $base = new Base();
        $this->assertequals($base->getBaseApiUrl().'/nodes?limit=10&offset=20', $base->getNextUrl($this->createOptions(10, 10)));
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Base::getPreviousUrl
     */
    public function testGetPreviousUrl()
    {
        $this->setServerParams('on', 'bar.com', '/api/v1/nodes', 'limit=10&offset=20');
        $base = new Base();
        $this->assertEquals($base->getBaseApiUrl().'/nodes?limit=10&offset=10', $base->getPreviousUrl($this->createOptions(20, 10)));
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Base::getFirstUrl
     */
    public function testGetFirstUrl()
    {
        $this->setServerParams('on', 'foo.com', '/api/v1/foos', 'limit=10&offset=30');
        $base = new Base();
        $this->assertEquals($base->getBaseApiUrl().'/foos?limit=10&offset=0', $base->getFirstUrl($this->createOptions(30, 10)));
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Base::getFirstUrl
     */
    public function testGetLastUrl()
    {
        $this->setServerParams('on', 'bar.com', '/api/v1/bars', 'limit=10&offset=20');
        $base = new Base();
        $this->assertEquals($base->getBaseApiUrl().'/bars?limit=10&offset=40', $base->getLastUrl($this->createOptions(20, 10), 41));
    }
    
}