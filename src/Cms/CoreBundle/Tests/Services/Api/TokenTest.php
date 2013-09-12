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
     * @coversNothing
     */
    public function createSiteForValidation($clientId, $clientSecret)
    {
        $site = $this->getMock('Cms\CoreBundle\Document\Site', array('getId', 'getClientSecret'));
        $site->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($clientId));
        $site->expects($this->any())
            ->method('getClientSecret')
            ->will($this->returnValue($clientSecret));
        return $site;
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
        $clientId = 'fooAndBar';
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
     * @covers Cms\CoreBundle\Services\Api\Token::getVersion
     */
    public function testSetTokenAndGetRawClientSecretAndGetClientIdAndGetVersionForTokenVersionOne()
    {
        $secret = $this->service->createSecret();
        $clientId = 'foobar';
        $version = '1';
        $token = $this->service->createToken($clientId, $secret, $version);
        $this->service->setToken($token);
        $this->assertEquals($secret, $this->service->getRawClientSecret());
        $this->assertEquals($clientId, $this->service->getClientId());
        $this->assertEquals($version, $this->service->getVersion());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Token::validateAuth
     * @covers Cms\CoreBundle\Services\Api\Token::setSite
     */
    public function testValidateAuthWhenValid()
    {
        $clientId = 'foo';
        $clientSecret = $this->service->createSecret();
        $token = $this->service->createToken($clientId, $clientSecret);
        $this->service->setToken($token);
        $site = $this->createSiteForValidation($clientId, $clientSecret);
        $this->service->setSite($site);
        $this->assertTrue($this->service->validateAuth());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Token::validateAuth
     * @expectedException RuntimeException
     */
    public function testValidateAuthWhenClientIdIsInvalid()
    {
        $clientId = 'foobar';
        $clientSecret = $this->service->createSecret();
        $token = $this->service->createToken('notFooBar', $clientSecret);
        $this->service->setToken($token);
        $site = $this->createSiteForValidation($clientId, $clientSecret);
        $this->service->setSite($site);
        $this->assertNull($this->service->validateAuth());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\Token::validateAuth
     * @expectedException Cms\CoreBundle\Services\Api\ApiException
     */
    public function testValidateAuthWhenClientSecretIsInvalid()
    {
        $clientId = 'bar';
        $clientSecret = $this->service->createSecret();
        $token = $this->service->createToken($clientId, 'notClientsSecret');
        $this->service->setToken($token);
        $site = $this->createSiteForValidation($clientId, $clientSecret);
        $this->service->setSite($site);
        $this->assertNull($this->service->validateAuth());
    }

}