<?php

namespace Cms\CoreBundle\Tests\Services\Acl;

use Cms\CoreBundle\Services\Acl\Helper;
use Cms\UserBundle\Document\User;
use Cms\CoreBundle\Document\Base;
use Cms\CoreBundle\Document\Site;
use Cms\CoreBundle\Document\Group;

class HelperTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Helper
     */
    private $service;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Concrete
     */
    private $object;

    /**
     * @var Site
     */
    private $site;

    /**
     * @coversNothings
     */
    public function setUp()
    {
        $this->service = new Helper();
        $this->user = new User();
        $this->object = new Concrete();
        $this->site = new Site();
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testOtherIsEmptyHasPermission()
    {
        $this->user->setId('1234');
        $group = new Group();
        $group->setName('foo')->setUserIds(array('1234'));
        $this->site->addGroup($group);
        $this->assertTrue( $this->service->hasPermission($this->user, 'r', $this->object, $this->site) );
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testOtherHasPermission()
    {
        $this->user->setId('1234');
        $group = new Group();
        $group->setName('foo')->setUserIds(array('1234'));
        $this->site->addGroup($group);
        $this->object->setAclOther(array(
            'permissions' => array('r', 'w')
        ));
        $this->assertTrue( $this->service->hasPermission( $this->user, 'w', $this->object, $this->site ));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testDeniedOtherHasPermission()
    {

        $this->object->setAclOther(array(
            'permissions' => array(),
        ));
        $this->assertFalse( $this->service->hasPermission( $this->user, 'w', $this->object, $this->site));
        $this->object->setAclOther(array(
            'permissions' => array('r')
        ));
        $this->assertFalse($this->service->hasPermission( $this->user, 'w', $this->object, $this->site ));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testOwnerHasPermission()
    {
        $this->user->setId('1234');
        $this->object->setAcl(array(
            'owner' => array(
                'id' => $this->user->getId(),
                'permissions' => array('r', 'w'),
            ),
            'other' => array(
                'permissions' => array('r'),
            ),
            'group' => array(),
        ));
        $this->assertTrue ($this->service->hasPermission( $this->user, 'r', $this->object, $this->site ));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testNotOwnerHasPermissions()
    {
        $this->user->setId('342');
        $this->object->setAcl(array(
            'owner' => array(
                'id' => '1234',
                'permissions' => array('x'),
            ),
            'other' => array(
                'permissions' => array('r'),
            ),
            'group' => array(),
        ));
        $this->assertFalse($this->service->hasPermission($this->user, 'w', $this->object, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testOwnerDeniedHasPermissions()
    {
        $this->user->setId('342');
        $this->object->setAcl(array(
            'owner' => array(
                'id' => '1234',
                'permissions' => array('r', 'w'),
            ),
            'other' => array(
                'permissions' => array('r'),
            ),
            'group' => array(),
        ));
        $this->assertFalse($this->service->hasPermission($this->user, 'x', $this->object, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testGroupHasPermission()
    {
        $group = new Group();
        $group->setId('789');
        $group->setUserIds(array('1234'));
        $this->site->addGroup($group);

        $this->user->setId('1234');

        $this->object->setAcl(array(
            'other' => array(
                'permissions' => array('r'),
            ),
            'owner' => array(
                'id' => 'bbd4',
                'permissions' => array('w'),
            ),
            'group' => array(
                'id' => '789',
                'permissions' => array('r', 'w', 'x'),
            ),
        ));
        $this->assertTrue($this->service->hasPermission($this->user, 'x', $this->object, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testGroupDeniedUserNotInGroupHasPermission()
    {
        $group = new Group();
        $group->setId('789');
        $group->setUserIds(array('bbsdf', 'hidg'));
        $this->site->addGroup($group);

        $this->user->setId('1234');

        $this->object->setAcl(array(
            'other' => array(
                'permissions' => array('r'),
            ),
            'owner' => array(
                'id' => 'bbd4',
                'permissions' => array('w'),
            ),
            'group' => array(
                'id' => '789',
                'permissions' => array('r', 'w', 'x'),
            ),
        ));
        $this->assertFalse($this->service->hasPermission($this->user, 'x', $this->object, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testGroupNotFoundHasPermission()
    {
        $group = new Group();
        $group->setId('Hg5dks3');
        $group->setUserIds(array('1234'));
        $this->site->addGroup($group);

        $this->user->setId('1234');

        $this->object->setAcl(array(
            'other' => array(
                'permissions' => array('r'),
            ),
            'owner' => array(
                'id' => 'bbd4',
                'permissions' => array('w'),
            ),
            'group' => array(
                'id' => '789',
                'permissions' => array('r', 'w', 'x'),
            ),
        ));
        $this->assertFalse($this->service->hasPermission($this->user, 'x', $this->object, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testGroupIsEmptyHasPermission()
    {
        $this->object->setAcl(array(
            'other' => array(
                'permissions' => array('r'),
            ),
            'owner' => array(
                'id' => 'bbd4',
                'permissions' => array('w'),
            ),
            'group' => array(),
        ));
        $this->assertFalse($this->service->hasPermission($this->user, 'x', $this->object, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testGroupDeniedHasPermissions()
    {
        $this->user->setId('1234');
        $this->object->setAcl(array(
            'other' => array(
                'permissions' => array('r'),
            ),
            'owner' => array(
                'id' => 'bbd4',
                'permissions' => array('w'),
            ),
            'group' => array(
                'id' => '543',
                'permissions' => array('r', 'w'),
            ),
        ));
        $this->assertFalse($this->service->hasPermission($this->user, 'x', $this->object, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::hasPermission
     */
    public function testSuperGroupHasPermission()
    {
        $group = new Group();
        $group->setId('super88dd');
        $group->setName('supers');
        $group->setUserIds(array('1234'));
        $this->site->addGroup($group);

        $this->user->setId('1234');

        $this->object->setAcl(array(
            'other' => array(
                'permissions' => array('r'),
            ),
            'owner' => array(
                'id' => 'bbd4',
                'permissions' => array('w'),
            ),
            'group' => array(
                'id' => '789',
                'permissions' => array('r', 'w', 'x'),
            ),
        ));
        $this->assertTrue($this->service->hasPermission($this->user, 'x', $this->object, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::isSuper
     */
    public function TestIsSuper()
    {
        $group = new Group();
        $group->setId('super88dd');
        $group->setName('supers');
        $group->setUserIds(array('1234'));

        $this->user->setId('1234');

        $this->site->addGroup($group);
        $this->assertTrue($this->service->isSuper($this->user, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::isSuper
     */
    public function TestIsNotSuper()
    {
        $group = new Group();
        $group->setId('super88dd');
        $group->setName('supers');
        $group->setUserIds(array('891'));

        $this->user->setId('1234');

        $this->site->addGroup($group);
        $this->assertFalse($this->service->isSuper($this->user, $this->site));
    }

    /**
     * @covers Cms\CoreBundle\Services\Acl\Helper::createAcl
     */
    public function testCreateAcl()
    {
        $acl = $this->service->createAcl(array('r', 'w', 'x'), array('r', 'w', 'x'), array('r'), '1234');
        $this->assertEquals($acl['owner'], array(
            'id' => null,
            'permissions' => array('r', 'w', 'x'),
        ));
        $this->assertEquals($acl['group'], array(
            'id' => '1234',
            'permissions' => array('r', 'w', 'x'),
        ));
        $this->assertEquals($acl['other'], array(
            'permissions' => array('r'),
        ));
    }

}

class Concrete extends Base {}