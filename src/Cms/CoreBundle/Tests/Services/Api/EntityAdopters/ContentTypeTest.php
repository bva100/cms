<?php


namespace Cms\CoreBundle\Tests\EntityAdopters;

use Cms\CoreBundle\Document\ContentType;
use Cms\CoreBundle\Services\Api\EntityAdopters\ContentTypeAdopter;

class ContentTypeTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ContentType
     */
    private $contentType;

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->contentType = new ContentType();
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\EntityAdopters\ContentTypeAdopter::setResource
     */
    public function testSetResourceAndGetResource()
    {
        $adopter = new ContentTypeAdopter();
        $adopter->setResource($this->contentType);
        $this->assertInstanceOf('Cms\CoreBundle\Document\ContentType', $adopter->getResource());
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\EntityAdopters\ContentTypeAdopter::convert
     */
    public function testConvert()
    {
        $obj = new \stdClass;
        $obj->id = null;
        $obj->name = 'dog';
        $obj->description = 'woof woof woof';
        $obj->slugPrefix = 'woof/';
        $obj->fields = array('foo' => 'bar');
        $obj->created = time();
        $obj->updated = null;
        $obj->categories = array();
        $obj->tags = null;
        $pretype = $this->contentType;
        $pretype->setName($obj->name)->setDescription($obj->description)->setSlugPrefix($obj->slugPrefix)->setFields($obj->fields)->setCreated($obj->created);
        $adopter = new ContentTypeAdopter();
        $adopter->setResource($pretype);
        $type = $adopter->convert();
        $this->assertEquals($obj, $type);
    }

    /**
     * @covers Cms\CoreBundle\Services\Api\EntityAdopters\ContentTypeAdopter::convert
     */
    public function testGetFromArray()
    {
        $contentType = new ContentType();
        $contentType->setName('foo');
        $contentType->setDescription('bar');
        $contentType->setSlugPrefix('bar/');
        $contentType->setFields(array('foo' => 'bar'));

        $params = array(
            'name' => 'boom', 'description' => 'a big boom', 'slugPrefix' => 'boomBangBoom/', 'fields' => array('big' => 'boom')
        );

        $adopter = new ContentTypeAdopter();
        $adopter->setResource($contentType);
        $converted = $adopter->getFromArray($params);

        $this->assertEquals($params['name'], $converted->getName());
        $this->assertEquals($params['description'], $converted->getDescription());
        $this->assertEquals($params['slugPrefix'], $converted->getSlugPrefix());
        $this->assertEquals($params['fields'], $converted->getFields());
    }
    
}