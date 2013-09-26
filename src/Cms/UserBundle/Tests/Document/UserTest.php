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

    /**
     * @covers Cms\UserBundle\Document\User::addIp
     * @covers Cms\UserBundle\Document\User::removeIp
     * @covers Cms\UserBundle\Document\User::getIps
     */
    public function testAddIpAndRemoveIp()
    {
        $this->user->addIp('1234');
        $this->user->addIp('1234');
        $this->user->addIp('456');
        $this->user->addIp('456');
        $user = $this->user->addIp('bcsf');
        $this->assertEquals($user, $this->user);
        $this->assertCount(3, $this->user->getIps());
        $this->assertEquals(array('1234', '456', 'bcsf'), $this->user->getIps());

        $user = $this->user->removeIp('bcsf');
        $this->assertEquals($user, $this->user);
        $this->assertCount(2, $this->user->getIps());
        $this->assertEquals(array('1234', '456'), $this->user->getIps());
        $this->user->removeIp('456');
        $this->assertCount(1, $this->user->getIps());
        $this->user->removeIp('1234');
        $this->assertEmpty($this->user->getIps());
    }

    /**
     * @covers Cms\UserBundle\Document\User::hasIP
     * @covers Cms\UserBundle\Document\User::addIp
     */
    public function testHasIp()
    {
        $this->user->addIp('123');
        $this->user->addIp('456');
        $this->user->addIp('abc');
        $this->assertTrue($this->user->hasIp('123'));
        $this->assertTrue($this->user->hasIp('456'));
        $this->assertTrue($this->user->hasIp('abc'));

        $this->assertFalse($this->user->hasIp('lkjsdf'));
        $this->assertFalse($this->user->hasIp('foobar'));
    }

    /**
     * @covers Cms\UserBundle\Document\User::addSiteId
     * @covers Cms\UserBundle\Document\User::getSiteIds
     * @covers Cms\UserBundle\Document\User::removeSiteId
     */
    public function testAddSiteIdAndRemoveSiteId()
    {
        $this->user->addSiteId('123');
        $this->user->addSiteId('123');
        $this->user->addSiteId('456');
        $this->user->addSiteId('456');
        $user = $this->user->addSiteid('789');
        $this->assertEquals($user, $this->user);
        $this->assertCount(3, $this->user->getSiteIds());
        $this->assertEquals(array('123', '456', '789'), $this->user->getSiteIds());

        $user = $this->user->removeSiteId('123');
        $this->assertEquals($user, $this->user);
        $this->assertCount(2, $this->user->getSiteIds());
        $this->assertEquals(array('456', '789'), $this->user->getSiteIds());
        $this->user->removeSiteId('456');
        $this->assertCount(1, $this->user->getSiteIds());
        $this->assertEquals(array('789'), $this->user->getSiteIds());
        $this->user->removeSiteId('789');
        $this->assertEmpty($this->user->getSiteIds());
    }

    /**
     * @covers Cms\UserBundle\Document\User::addSiteId
     * @covers Cms\UserBundle\Document\User::hasSiteId
     */
    public function testHasSiteId()
    {
        $this->user->addSiteId('123');
        $this->user->addSiteId('456');
        $this->user->addSiteid('789');
        $this->assertTrue($this->user->hasSiteid('123'));
        $this->assertTrue($this->user->hasSiteid('456'));
        $this->assertTrue($this->user->hasSiteid('789'));
        $this->assertFalse($this->user->hasSiteId('12345'));
        $this->assertFalse($this->user->hasSiteId('2134234'));
    }


}