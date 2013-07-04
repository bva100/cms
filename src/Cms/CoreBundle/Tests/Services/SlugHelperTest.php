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
     */
    public function testSetFullSlug()
    {
        $fullSlug = 'foo/bar';
        $this->slugHelper->setFullSlug($fullSlug);
        echo '<pre>', \var_dump($this->slugHelper); die();
    }


}