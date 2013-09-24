<?php


namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\Acl;

class AclTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Acl
     */
    private $acl;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->acl = new Acl();
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
        $this->assertTrue( $this->acl->validateValueObject($array, 'testObject') );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidateObjectFailedId()
    {
        $array = array(
            'permissions' => array('r'),
        );
        $this->acl->validateValueObject($array, 'testObject');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidateObjectFailedPermissions()
    {
        $array = array(
            'id' => 'foobar12',
        );
        $this->acl->validateValueObject($array, 'testObject');
    }

    /**
     * @covers Cms\CoreBundle\Document\Acl::setOwner
     * @covers Cms\CoreBundle\Document\Acl::getOwner
     */
    public function testSetOwnerAndGetOwner()
    {
        $owner = array(
            'id' => '1234',
            'permissions' => array('r', 'w', 'x'),
        );
        $acl = $this->acl->setOwner($owner);
        $this->assertEquals($owner, $this->acl->getOwner());
        $this->assertEquals($acl, $this->acl);
    }

    /**
     * @covers Cms\CoreBundle\Document\Acl::setGroup
     * @covers Cms\CoreBundle\Document\Acl::getGroup
     */
    public function testSetGroupAndGetGroup()
    {
        $group = array(
            'id' => '456b',
            'permissions' => array('r', 'w'),
        );
        $acl = $this->acl->setGroup($group);
        $this->assertEquals($group, $this->acl->getGroup());
        $this->assertEquals($acl, $this->acl);
    }

    /**
     * @covers Cms\CoreBundle\Document\Acl::setOther
     * @covers Cms\CoreBundle\Document\Acl::getOther
     */
    public function testSetOtherAndGetOther()
    {
        $other = array(
            'permissions' => array('r'),
        );
        $acl = $this->acl->setOther($other);
        $this->assertEquals($other, $this->acl->getOther());
        $this->assertEquals($acl, $this->acl);
    }

}