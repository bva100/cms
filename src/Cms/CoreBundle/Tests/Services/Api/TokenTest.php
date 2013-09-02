<?php
/**
 * User: Brian Anderson
 * Date: 8/31/13
 * Time: 1:19 PM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Services\Api\Token;

/**
 * Class AssetManagerTest
 * @package Cms\CoreBundle\Tests
 */
class ApiTokenTest extends \PHPUnit_Framework_TestCase {

    private $service;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->service = new Token();
    }

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->service = new Token();
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Token::createSecret
     */
    public function testCreateSecret()
    {
        $secret = $this->service->createSecret();
        $this->assertNotEmpty($secret);
        $this->assertGreaterThan(12, strlen($secret));
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Token::createSecret
     * @covers Cms\CoreBundle\Services\Api\Token::createToken
     * @covers Cms\CoreBundle\Services\Api\Token::getRawClientSecret
     * @covers Cms\CoreBundle\Services\Api\Token::getClientId
     */
    public function testCreateTokenAndGetRawClientSecretAndGetClientId()
    {
        $secret = $this->service->createSecret();
        $clientId = '51c0033d18a5162c04000002';
        $token = $this->service->createToken($clientId, $secret);
        $tokenUrlSafe = urlencode($token);
        $this->assertEquals($token, $tokenUrlSafe);
        $this->service->setClientIdAndRawClientSecret($token);
        $this->assertEquals($secret, $this->service->getRawClientSecret());
        $this->assertEquals($clientId, $this->service->getClientId());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Token::setToken
     * @covers Cms\CoreBundle\Services\Api\Token::getRawClientSecret
     * @covers Cms\CoreBundle\Services\Api\Token::getClientId
     */
    public function testSetTokenAndGetRawClientSecretAndGetClientId()
    {
        $secret = $this->service->createSecret();
        $clientId = 'foobar';
        $token = $this->service->createToken($clientId, $secret);
        $this->service->setToken($token);
        $this->assertEquals($secret, $this->service->getRawClientSecret());
        $this->assertEquals($clientId, $this->service->getClientId());
    }

}