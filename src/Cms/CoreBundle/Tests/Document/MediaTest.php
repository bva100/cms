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
        $this->media->addContentTypeId('1');
        $this->media->addContentTypeId('2');
        $this->media->addContentTypeId('3');
        $this->assertCount(3, $this->media->getContentTypeIds());
        $this->assertEquals(array('1', '2', '3'), $this->media->getContentTypeIds());

        $this->media->removeContentTypeId('2');
        $this->assertCount(2, $this->media->getContentTypeIds());
        $this->assertEquals(array('1', '3'), $this->media->getContentTypeIds());
        $this->media->removeContentTypeId('1');
        $this->assertEquals(array('3'), $this->media->getContentTypeIds());
        $this->media->removeContentTypeId('3');
        $this->assertEmpty($this->media->getContentTypeIds());
    }

}