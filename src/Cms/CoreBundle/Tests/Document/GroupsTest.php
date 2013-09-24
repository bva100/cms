<?php


namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\Group;

class GroupsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Group
     */
    private $group;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->group = new Group;
    }

    /**
     * @covers Cms\CoreBundle\Document\Group::__construct
     */
    public function testConstruct()
    {
        $this->assertInternalType('array', $this->group->getUserIds());
        $this->assertInternalType('int', $this->group->getCreated());
    }

    /**
     * @covers Cms\CoreBundle\Document\Group::setName
     * @covers Cms\CoreBundle\Document\Group::getName
     */
    public function testSetNameAndGetName()
    {
        $name = 'foo';
        $group = $this->group->setName($name);
        $this->assertEquals($name, $this->group->getName());
        $this->assertEquals($group, $this->group);
    }

    /**
     * @covers Cms\CoreBundle\Document\Group::setUserIds
     * @covers Cms\CoreBundle\Document\Group::getuserIds
     */
    public function testSetUserIdsAndGetUserIds()
    {
        $userIds = array('fo234', 'bar123', '123');
        $group = $this->group->setUserIds($userIds, $this->group->setUserIds($userIds));
        $this->assertEquals($userIds, $this->group->getUserIds());
        $this->assertEquals($group, $this->group);
    }

    /**
     * @covers Cms\CoreBundle\Document\Group::addUserId
     * @covers Cms\CoreBundle\Document\Group::removeUserId
     */
    public function testAddUserIdAndRemoveUserId()
    {
        $userId = 'foobar1223';
        $userId2 = 'bang2';
        $userId3 = 'boom3';
        $group = $this->group->addUserId($userId);
        $this->assertCount(1, $this->group->getUserIds());
        $this->assertEquals(array($userId), $this->group->getUserIds());
        $this->assertEquals($group, $this->group);

        $this->group->addUserId($userId);
        $this->assertCount(1, $this->group->getUserIds());
        $this->group->addUserId($userId2);
        $this->assertCount(2, $this->group->getUserIds());
        $this->group->addUserId($userId3);
        $this->assertCount(3, $this->group->getUserIds());
        $this->assertEquals(array($userId, $userId2, $userId3), $this->group->getUserIds());
        $group = $this->group->removeUserId($userId);
        $this->assertCount(2, $this->group->getUserIds());
        $this->group->removeUserId($userId2);
        $this->assertEquals(array($userId3), $this->group->getUserIds());
        $this->group->removeUserId($userId3);
        $this->assertEmpty($this->group->getUserIds());
        $this->assertEquals($group, $this->group);
    }

    /**
     * @covers Cms\CoreBundle\Document\Group::hasUserId
     */
    public function testHasUserId()
    {
        $userId = 'foobar';
        $this->group->addUserId($userId);
        $this->assertTrue($this->group->hasUserId($userId));
        $this->assertFalse($this->group->hasUserId('boo'));
    }

}