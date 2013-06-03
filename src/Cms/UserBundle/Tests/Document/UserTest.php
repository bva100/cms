<?php
/**
 * User: Brian Anderson
 * Date: 6/2/13
 * Time: 7:13 PM
 */

namespace Cms\UserBundle\Tests\Document;

use Cms\UserBundle\Document\User;

/**
 * Class UserTest
 * @package Cms\UserBundle\Tests
 */
class UserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Cms\UserBundle\Document\User
     */
    private $user;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->user = new User();
    }

    /**
     * @covers Cms\UserBundle\Document\User::getSalt
     * @covers Cms\UserBundle\Document\User::setSaltGroupIndex
     */
    public function testGetSalt()
    {
        $this->user->setSaltGroupIndex(1);
        $salt = $this->user->getSalt();
        $this->assertEquals('190qrg9jTkJZAfYJi7Ho', $salt);

        $this->user->setSaltGroupIndex(3);
        $salt = $this->user->getSalt();
        $this->assertEquals('HsWmG9w3CpXEp81Qkkiq', $salt);
    }

}