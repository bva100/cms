<?php
/**
 * User: Brian Anderson
 * Date: 7/4/13
 * Time: 4:19 PM
 */

use Cms\CoreBundle\Services\SlugHelper;

/**
 * Class SlugHelperTest
 */
class SlugHelperTest extends \PHPUnit_Framework_TestCase {


    /**
     * @var Cms\CoreBundle\Services\SlugHelper
     */
    private $slugHelper;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->slugHelper = new SlugHelper();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->slugHelper = new SlugHelper();
    }

    /**
     * @covers Cms\CoreBundle\Services\SlugHelper::setFullSlug
     * @covers Cms\CoreBundle\Services\SlugHelper::getSlugArray
     * @covers Cms\CoreBundle\Services\SlugHelper::getSlug
     * @covers Cms\CoreBundle\Services\SlugHelper::getSlugPrefix
     */
    public function testSetFullSlugAndGetPrefixAndTestSlug()
    {
        $fullSlug = 'foo/bar';
        $this->slugHelper->setFullSlug($fullSlug);
        $this->assertEquals(array('foo', 'bar'), $this->slugHelper->getSlugArray());
        $this->assertEquals('foo', $this->slugHelper->getSlugPrefix());
        $this->assertEquals('bar', $this->slugHelper->getSlug());

        $fullSlug = 'foo/bar/baz';
        $this->slugHelper->setFullSlug($fullSlug);
        $this->assertEquals(array('foo', 'bar', 'baz'), $this->slugHelper->getSlugArray());
        $this->assertEquals('foo', $this->slugHelper->getSlugPrefix());
        $this->assertEquals('bar/baz', $this->slugHelper->getSlug());
    }

    /**
     * @covers Cms\CoreBundle\Services\SlugHelper::isSlugTitle
     * @covers Cms\CoreBundle\Services\SlugHelper::setTitle
     * @covers Cms\CoreBundle\Services\SlugHelper::setFullSlug
     */
    public function testIsTitleSlug()
    {
        $fullSlug = 'foo/bar-boom';
        $title = 'bar boom';
        $this->slugHelper->setFullSlug($fullSlug);
        $this->slugHelper->setTitle($title);
        $this->assertTrue($this->slugHelper->isTitleSlug());

        $title = 'bazboom';
        $this->slugHelper->setTitle($title);
        $this->assertFalse($this->slugHelper->isTitleSlug());
    }

}