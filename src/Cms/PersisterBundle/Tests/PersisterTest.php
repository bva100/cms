<?php
/**
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 5:21 PM
 */

namespace Cms\PersisterBundle\Tests;

use Cms\PersisterBundle\Services\Persister;

/**
 * Class PersisterTest
 * @package Cms\PersisterBundle\Tests
 */
class PersisterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->em = $this->getMock('EntityManager', array('persist', 'flush', 'remove', 'getRepository'));
        $this->validator = $this->getMock('Validator', array('validate'));
        $this->persister = new Persister($this->em, $this->validator);
    }

    /**
     * @covers Cms\PersisterBundle\Services\Persister::save
     * @expectedException InvalidArgumentException
     */
    public function testInvalidFlashArray()
    {
        $obj = new \stdClass;
        $this->persister->save($obj, true, array('flashBag' => ''));
    }

    /**
     * @covers Cms\PersisterBundle\Services\Persister::save
     */
    public function testSaveInvalid()
    {
        $this->validator->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(array('nada')));
        $this->persister->setValidator($this->validator);

        $results = $this->persister->save(new \stdClass(), true);
        $this->assertFalse($results);
    }

    /**
     * @covers Cms\PersisterBundle\Services\Persister::save
     */
    public function testSaveValid()
    {
        $this->validator->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(array()));
        $this->persister->setValidator($this->validator);

        // test create
        $obj = $this->getMock('object', array('getId'));
        $obj->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(null));
        $results = $this->persister->save($obj);
        $this->assertTrue($results);

        // test update
        $obj->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('dshjs6'));
        $results = $this->persister->save($obj);
        $this->assertTrue($results);
    }

    /**
     * @covers Cms\PersisterBundle\Services\Persister::delete
     */
    public function testDelete()
    {
        $obj = new \stdClass;
        $results = $this->persister->delete($obj, false);
        $this->assertTrue($results);
    }

    /**
     * @covers Cms\PersisterBundle\Services\Persister::getRepo
     */
    public function testGetRepo()
    {
        $this->em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue('returnedTestRepo'));
        $this->persister->setEm($this->em);
        
        $repo = $this->persister->getRepo('testRepo');
        $this->assertEquals($repo, 'returnedTestRepo');
    }
    
}