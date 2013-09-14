<?php
/**
 * User: Brian Anderson
 * Date: 9/12/13
 * Time: 10:56 PM
 *
 * NOTE: this test also test the abstract class' method called "addObjProperty"
 *
 */

namespace Cms\CoreBundle\Tests\EntityAdopters;

use \PHPUnit_Framework_TestCase as PhpUnit;
use Cms\CoreBundle\Services\Api\EntityAdopters\NodeAdopter;
use Cms\CoreBundle\Document\Node as Node;

class NodeTest extends PhpUnit {

    private $node;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->node = new Node();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->node = new Node();
        $this->node->setTitle('foobar');
        $this->node->setDomain('foobar.com');
        $this->node->setLocale('en');
        $this->node->setTags(array('foo', 'bar'));
        $this->node->setSlug('/bar/foobar');
        $this->node->setCategories(array('programming'));
        $this->node->setDescription('foo and bar');
        $this->node->addMetatag(array('someMetaTagName' => 'someMetaTagValue'));
        $this->node->setFields(array('randomFoo' => 'bigBar'));
        $this->node->setAuthor(array('firstName' => 'Foo'));
        $this->node->setImage('http://foobar.com/images/foobarPic.png');
    }

    /**
     * @covers Cms\CoreBundle\Tests\EntityAdopters\NodeAdopter::convert
     * @covers Cms\CoreBundle\Tests\EntityAdopters\NodeAdopter::setResource
     * @covers Cms\CoreBundle\Tests\EntityAdopters\AbstractAdopter::addObjProperty
     */
    public function testConvertNoFieldsDefined()
    {
        $adopter = new NodeAdopter();
        $adopter->setResource($this->node);
        $obj = $adopter->convert();
        $this->assertEquals($obj->id, $this->node->getId());
        $this->assertEquals($obj->domain, $this->node->getDomain());
        $this->assertEquals($obj->locale, $this->node->getLocale());
        $this->assertEquals($obj->categories, $this->node->getCategories());
        $this->assertEquals($obj->tags, $this->node->getTags());
        $this->assertEquals($obj->slug, $this->node->getSlug());
        $this->assertEquals($obj->title, $this->node->getTitle());
        $this->assertEquals($obj->description, $this->node->getDescription());
        $this->assertEquals($obj->metatags, $this->node->getMetatags());
        $this->assertEquals($obj->fields, $this->node->getFields());
        $this->assertEquals($obj->author, $this->node->getAuthor());
        $this->assertEquals($obj->image, $this->node->getImage());
        $this->assertEquals($obj->created, $this->node->getCreated());
    }

    public function testConvertWithFieldRestrictions()
    {
        $fields = array('title', 'locale', 'slug');
        $adopter = new NodeAdopter();
        $adopter->setResource($this->node);
        $obj = $adopter->convert($fields);
        $this->assertCount(3, get_object_vars($obj));
        $this->assertEquals($obj->title, $this->node->getTitle());
        $this->assertEquals($obj->locale, $this->node->getLocale());
        $this->assertEquals($obj->slug, $this->node->getSlug());
    }

}