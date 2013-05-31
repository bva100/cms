<?php
/**
 * User: Brian Anderson
 * Date: 5/31/13
 * Time: 1:35 PM
 */

namespace Cms\CoreBundle\Tests;


use Cms\CoreBundle\Services\AssetManager;

/**
 * Class AssetManagerTest
 * @package Cms\CoreBundle\Tests
 */
class AssetManagerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cms\CoreBundle\Services\AssetManager
     */
    protected $assetManager;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->assetManager = new AssetManager();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->assetManager = new AssetManager();
    }

    public function testCreateAndRead()
    {
        $name = '_testCreate';
        $ext = 'css';
        $data = 'body p { background-color: red }';
        $this->assetManager->save($name, $ext, $data);
        $results = $this->assetManager->read($name, $ext);
        $this->assertEquals($results, $data);
    }

}