<?php
/**
 * User: Brian Anderson
 * Date: 6/4/13
 * Time: 10:18 AM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\Media;

/**
 * Class NodeTest
 * @package Cms\CoreBundle\Tests
 */
class MediaTest extends \PHPUnit_Framework_TestCase {

    private $media;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->media = new Media();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->media = new Media();
    }

    /**
     * @covers Cms\CoreBundle\Document\Media::addConentTypeId
     * @covers Cms\CoreBundle\Document\Media::getConentTypeIds
     * @covers Cms\CoreBundle\Document\Media::removeContentTypeId
     */
    public function testAddContentTypeIds()
    {
        $this->media->addNodeId('1');
        $this->media->addNodeId('2');
        $this->media->addNodeId('3');
        $this->assertCount(3, $this->media->getNodeIds());
        $this->assertEquals(array('1', '2', '3'), $this->media->getNodeIds());

        $this->media->removeNodeId('2');
        $this->assertCount(2, $this->media->getNodeIds());
        $this->assertEquals(array('1', '3'), $this->media->getNodeIds());
        $this->media->removeNodeId('1');
        $this->assertEquals(array('3'), $this->media->getNodeIds());
        $this->media->removeNodeId('3');
        $this->assertEmpty($this->media->getNodeIds());
    }

    public function testSetFieldsAndGetFields()
    {
        $fields = array(
            'foo' => 'bar',
            'practice santeria?' => false,
            'sublime' => array('songs' => array('what i got', 'badfish')),
        );
        $this->media->setFields($fields);
        $this->assertEquals($fields, $this->media->getFields());
    }

}