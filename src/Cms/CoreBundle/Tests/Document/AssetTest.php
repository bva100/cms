<?php
/**
 * User: Brian Anderson
 * Date: 6/1/13
 * Time: 10:12 AM
 */

namespace Cms\CoreBundle\Tests;

use Cms\CoreBundle\Document\Asset;

/**
 * Class AssetTest
 * @package Cms\CoreBundle\Tests
 */
class AssetTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \Cms\CoreBundle\Document\Asset
     */
    private $asset;

    /**
     * @coversNothing
     */
    public function __construct()
    {
        $this->asset = new Asset();
    }

    /**
     * @coversNothing
     */
    public function setUp()
    {
        $this->asset = new Asset();
    }

    /**
     * @covers \Cms\CoreBundle\Document\Asset::addHistory
     * @covers \Cms\CoreBundle\Document\Asset::getHistory
     */
    public function testAddHistory()
    {
        $this->asset->addHistory('foobar');
        $this->asset->addHistory('bar');
        $this->asset->addHistory('foo');

        $this->assertCount(3, $this->asset->getHistory());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Asset::removeAllHistory
     * @covers \Cms\CoreBundle\Document\Asset::addHistory
     * @covers \Cms\CoreBundle\Document\Asset::getHistory
     */
    public function testRemoveAllHistory()
    {
        $this->asset->addHistory('foobar');
        $this->asset->addHistory('bar');
        $this->asset->removeAllHistory();

        $this->assertEmpty($this->asset->getHistory());
    }

    /**
     * @covers \Cms\CoreBundle\Document\Asset::removeOldestHistory
     * @covers \Cms\CoreBundle\Document\Asset::addHistory
     * @covers \Cms\CoreBundle\Document\Asset::getHistory
     */
    public function testRemoveOldestHistory()
    {
        $this->asset->addHistory('foobar');
        $this->asset->addHistory('foo');
        $this->asset->addHistory('bar');
        $this->asset->removeOldestHistory();

        $history = $this->asset->getHistory();
        $firstHistoryEntry = $history['0'];
        $secondHistoryEntry = $history['1'];

        $this->assertCount(2, $history);
        $this->assertEquals('foobar', $firstHistoryEntry->content);
        $this->assertEquals('foo', $secondHistoryEntry->content);
    }

    /**
     * @covers \Cms\CoreBundle\Document\Asset::removeNewestHistory
     * @covers \Cms\CoreBundle\Document\Asset::addHistory
     * @covers \Cms\CoreBundle\Document\Asset::getHistory
     */
    public function testRemoveNewestHistory()
    {
        $this->asset->addHistory('foobar');
        $this->asset->addHistory('foo');
        $this->asset->addHistory('bar');
        $this->asset->removeNewestHistory();

        $history = $this->asset->getHistory();
        $firstHistoryEntry = $history['0'];
        $secondHistoryEntry = $history['1'];

        $this->assertCount(2, $history);
        $this->assertEquals('foo', $firstHistoryEntry->content);
        $this->assertEquals('bar', $secondHistoryEntry->content);
    }

}