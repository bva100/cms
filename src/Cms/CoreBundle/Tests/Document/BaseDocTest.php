<?php


namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\Base;

class BaseDocTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Base
     */
    private $concrete;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->concrete = new Concrete();
    }

    /**
     * @covers Cms\CoreBundle\Document\Acl::setOwner::validateValueObject
     */
    public function testValidateValueObject()
    {
        $array = array(
            'id' => 'foo',
            'permissions' => array('r', 'w', 'x'),
        );
        $this->assertTrue( $this->concrete->validateAclObject($array, 'testObject') );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidateObjectFailedId()
    {
        $array = array(
            'permissions' => array('r'),
        );
        $this->concrete->validateAclObject($array, 'testObject');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidateObjectFailedPermissions()
    {
        $array = array(
            'id' => 'foobar12',
        );
        $this->concrete->validateAclObject($array, 'testObject');
    }

    /**
     * @covers Cms\CoreBundle\Document\Acl::setOwner
     * @covers Cms\CoreBundle\Document\Acl::getOwner
     * @covers Cms\CoreBundle\Document\Acl::getOwnerId
     * @covers Cms\CoreBundle\Document\Acl::getOwnerPermissions
     */
    public function testSetOwnerAndGetOwnerAndGetOwnerIdAndGetOwnerPermissions()
    {
        $owner = array(
            'id' => '1234',
            'permissions' => array('r', 'w', 'x'),
        );
        $base = $this->concrete->setAclOwner($owner);
        $this->assertEquals($owner, $this->concrete->getAclOwner());
        $this->assertEquals($base, $this->concrete);
        $this->assertEquals($owner['id'], $this->concrete->getAclOwnerId());
        $this->assertEquals($owner['permissions'], $this->concrete->getAclOwnerPermissions());
    }

    /**
     * @covers Cms\CoreBundle\Document\Acl::setGroup
     * @covers Cms\CoreBundle\Document\Acl::getGroup
     * @covers Cms\CoreBundle\Document\Acl::getGroupId
     * @covers Cms\CoreBundle\Document\Acl::getGroupPermissions
     */
    public function testSetGroupAndGetGroup()
    {
        $group = array(
            'id' => '456b',
            'permissions' => array('r', 'w'),
        );
        $base = $this->concrete->setAclGroup($group);
        $this->assertEquals($group, $this->concrete->getAclGroup());
        $this->assertEquals($base, $this->concrete);
        $this->assertEquals($group['id'], $this->concrete->getAclGroupId());
        $this->assertEquals($group['permissions'], $this->concrete->getAclGroupPermissions());
    }

    /**
     * @covers Cms\CoreBundle\Document\Acl::setOther
     * @covers Cms\CoreBundle\Document\Acl::getOther
     * @covers Cms\CoreBundle\Document\Acl::getGroupPermissions
     */
    public function testSetOtherAndGetOther()
    {
        $other = array(
            'permissions' => array('r'),
        );
        $base = $this->concrete->setAclOther($other);
        $this->assertEquals($other, $this->concrete->getAclOther());
        $this->assertEquals($base, $this->concrete);
        $this->assertEquals($other['permissions'], $this->concrete->getAclOtherPermissions());
    }

}

class Concrete extends Base {}