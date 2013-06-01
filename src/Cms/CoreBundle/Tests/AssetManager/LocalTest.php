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
class LocalTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cms\CoreBundle\Services\AssetManager
     */
    protected $assetManager;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->assetManager = new AssetManager\Local();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->assetManager = new AssetManager\Local();
    }

    /**
     * @covers Cms\CoreBundle\Services\AssetManager::validateName
     * @expectedException InvalidArgumentException
     */
    public function testValidateName()
    {
        $badName = '../hacker.php';
        $this->assetManager->validateName($badName);
    }

    /**
     * @covers Cms\CoreBundle\Services\AssetManager::save
     * @covers Cms\CoreBundle\Services\AssetManager::read
     * @covers Cms\CoreBundle\Services\AssetManager::delete
     */
    public function testCreateAndReadAndDelete()
    {
        $name = '_testCreate';
        $ext = 'css';
        $data = 'body { background-color: red }';
        $this->assetManager->save($name, $ext, $data);
        $results = $this->assetManager->read($name, $ext);
        $this->assertEquals($results, $data);

        $this->assetManager->delete($name, $ext);
    }

}