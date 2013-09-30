<?php


namespace Cms\CoreBundle\Tests\EntityAdopters;


use Cms\CoreBundle\Document\Media;
use Cms\CoreBundle\Services\Api\EntityAdopters\MediaAdopter;

class MediaTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Media
     */
    private $media;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->media = new Media();
        $this->media->setFilename('foobar.png');
        $this->media->setStorage('s3');
        $this->media->setUrl('s3CloudFrontMaybe.com/foobar.png');
        $this->media->setMime('image/png');
        $this->media->setExt('png');
        $this->media->setSize(79083);
        $this->media->setMetadata(array('foo' => 'bar'));
        $this->media->setNodeIds(array('1','2','3'));
        $this->media->setFields(array('foo' => array('bug' => 'bird')));
        $this->media->setCreated(time());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\EntityAdopters\MediaAdopter::setResource
     */
    public function testSetResourceAndGetResource()
    {
        $adopter = new MediaAdopter();
        $adopter->setResource($this->media);
        $this->assertInstanceOf('Cms\CoreBundle\Document\Media', $adopter->getResource());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\EntityAdopters\MediaAdopter::convert
     */
    public function testConvert()
    {
        $adopter = new MediaAdopter();
        $adopter->setResource($this->media);
        $obj = $adopter->convert();
        $this->assertEquals($obj->storage, $this->media->getStorage());
        $this->assertEquals($obj->url, $this->media->getUrl());
        $this->assertEquals($obj->mime, $this->media->getMime());
        $this->assertEquals($obj->ext, $this->media->getExt());
        $this->assertEquals($obj->size, $this->media->getSize());
        $this->assertEquals($obj->metadata, $this->media->getMetadata());
        $this->assertEquals($obj->nodeIds, $this->media->getNodeIds());
        $this->assertEquals($obj->fields, $this->media->getFields());
        $this->assertEquals($obj->created, $this->media->getCreated());
    }

    public function testGetFromArray()
    {
        $params = array(
            'filename' => 'woof.jpg',
            'storage' => 'jupiter',
            'url' => 'damnTheseUnitTakesTakeALongTime.com/jupiter/woof.jpg',
            'mime' => 'image/jpg',
            'ext' => 'jpg',
            'size' => 2,
            'metadata' => array('silly' => 'goose'),
            'nodeIds' => array('1000', '354', '9'),
            'fields' => array('heros' => array('Goku', 'Gohan', 'Piccolo', 'Trunks')),
            'created' => time(),
        );
        $adopter = new MediaAdopter();
        $adopter->setResource($this->media);
        $newMedia = $adopter->getFromArray($params);
        $this->assertEquals($params['filename'], $newMedia->getFilename());
        $this->assertEquals($params['storage'], $newMedia->getStorage());
        $this->assertEquals($params['mime'], $newMedia->getMime());
        $this->assertEquals($params['ext'], $newMedia->getExt());
        $this->assertEquals($params['size'], $newMedia->getSize());
        $this->assertEquals($params['metadata'], $newMedia->getMetadata());
        $this->assertEquals($params['nodeIds'], $newMedia->getNodeIds());
        $this->assertEquals($params['fields'], $newMedia->getFields());
        $this->assertEquals($params['created'], $newMedia->getCreated());
    }

}