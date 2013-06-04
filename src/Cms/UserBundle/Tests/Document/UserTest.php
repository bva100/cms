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

    /**
     * @covers Cms\UserBundle\Document\User::addRole
     * @covers Cms\UserBundle\Document\User::getRoles
     */
    public function testAddRole()
    {
        $this->user->addRole('ROLE_ADMIN');
        $this->user->addRole('ROLE_USER');
        $roles = $this->user->getRoles();

        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    /**
     * @covers Cms\UserBundle\Document\User::removeRole
     * @covers Cms\UserBundle\Document\User::addRole
     * @covers Cms\UserBundle\Document\User::getRoles
     */
    public function testRemoveRole()
    {
        $this->user->addRole('ROLE_USER');
        $this->user->addRole('ROLE_ADMIN');
        $this->user->addRole('ROLE_ALLOWED_TO_SWITCH');
        $this->assertCount(3, $this->user->getRoles());

        $this->user->removeRole('ROLE_ALLOWED_TO_SWITCH');
        $this->assertCount(2, $this->user->getRoles());
        $this->user->removeRole('ROLE_ADMIN');
        $this->assertCount(1, $this->user->getRoles());
        $this->user->removeRole('ROLE_USER');
        $this->assertEmpty($this->user->getRoles());
    }

    /**
     * @covers Cms\UserBundle\Document\User::removeAllRole
     * @covers Cms\UserBundle\Document\User::addRole
     * @covers Cms\UserBundle\Document\User::getRoles
     */
    public function testRemoveAllRoles()
    {
        $this->user->addRole('ROLE_USER');
        $this->user->addRole('ROLE_ADMIN');
        $this->user->addRole('ROLE_ALLOWED_TO_SWITCH');
        $this->assertCount(3, $this->user->getRoles());

        $this->user->removeAllRoles();
        $this->assertEmpty($this->user->getRoles());
    }

    /**
     * @covers Cms\UserBundle\Document\User::setName
     * @covers Cms\UserBundle\Document\User::getName
     */
    public function testSetNameAndGetName()
    {
        $nameArray = array(
            'first' => 'Foo',
            'last'  => 'Bar',
            'middle' => 'Dum',
            'prefix' => 'Mr.',
            'suffix' => 'Ph.D',
        );
        $this->user->setName($nameArray);
        $this->assertEquals($nameArray, $this->user->getName());
        $this->assertEquals('Foo', $this->user->getName('first'));
        $this->assertEquals('Bar', $this->user->getName('last'));
        $this->assertEquals('Dum', $this->user->getName('middle'));
        $this->assertEquals('Mr.', $this->user->getName('prefix'));
        $this->assertEquals('Ph.D', $this->user->getName('suffix'));
        $this->assertEquals('Foo Bar', $this->user->getName('first_last'));
        $this->assertEquals('Foo B', $this->user->getName('short'));
    }

    /**
     * @covers Cms\UserBundle\Document\User::recordLogin
     * @covers Cms\UserBundle\Document\User::getLogin
     */
    public function testRecordLogin()
    {
        $this->user->recordLogin();
        $loginArray = $this->user->getLogin();

        $this->assertCount(2, $loginArray);
        $this->assertEquals(1, $loginArray['count']);

        $this->user->recordLogin();
        $loginArray = $this->user->getLogin();
        $this->assertEquals(2, $loginArray['count']);
    }
}