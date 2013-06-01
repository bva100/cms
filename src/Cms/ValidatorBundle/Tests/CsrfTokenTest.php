<?php
/**
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 10:41 PM
 */

namespace Cms\ValidatorBundle\Tests;


use Cms\ValidatorBundle\Services\CsrfToken;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class CsrfTokenTest
 * @package Cms\ValidatorBundle\Tests
 */
class CsrfTokenTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cms\ValidatorBundle\Services\CsrfToken
     */
    private $csrfToken;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $session = new Session( new MockArraySessionStorage());
        $this->csrfToken = new CsrfToken($session);
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $session = new Session( new MockArraySessionStorage());
        $this->csrfToken = new CsrfToken($session);
    }

    /**
     * @covers Cms\ValidatorBundle\Services\CsrfToken::createToken
     * @covers Cms\ValidatorBundle\Services\CsrfToken::getToken
     */
    public function testCreateAndGetToken()
    {
        $token = $this->csrfToken->getToken();
        $this->assertTrue((bool)$token);
    }

    /**
     * @covers Cms\ValidatorBundle\Services\CsrfToken::validate
     */
    public function testTokenValidate()
    {
        $token = $this->csrfToken->getToken();
        $results = $this->csrfToken->validate($token, false);
        $this->assertTrue($results);

        $token = 'foo';
        $results = $this->csrfToken->validate($token, false);
        $this->assertFalse($results);

        $token = '';
        $results = $this->csrfToken->validate($token, false);
        $this->assertFalse($results);

        $token = 1;
        $results = $this->csrfToken->validate($token, false);
        $this->assertFalse($results);

        $token = 0;
        $results = $this->csrfToken->validate($token, false);
        $this->assertFalse($results);

        $token = true;
        $results = $this->csrfToken->validate($token, false);
        $this->assertFalse($results);

        $token = null;
        $results = $this->csrfToken->validate($token, false);
        $this->assertFalse($results);

        $token = array();
        $results = $this->csrfToken->validate($token, false);
        $this->assertFalse($results);

        $token = new \stdClass();
        $results = $this->csrfToken->validate($token, false);
        $this->assertFalse($results);
    }

}