<?php
/**
 * User: Brian Anderson
 * Date: 6/11/13
 * Time: 11:12 AM
 */

namespace Cms\CoreBundle\Services\NodeLoader;


/**
 * Class CoreLoop
 * @package Cms\CoreBundle\Services\NodeLoader
 */
class CoreLoop {

    /**
     * @var \Cms\CoreBundle\Document\Node
     */
    private $node;

    /**
     * @var \Cms\CoreBundle\Repository\NodeRepository
     */
    private $nodeRepo;

    /**
     * @var array
     */
    private $params;

    /**
     * @var \Cms\CoreBundle\Services\NodeLoader\LoopFinder
     */
    private $loopFinder;

    public function __construct(\Cms\CoreBundle\Services\NodeLoader\LoopFinder $loopFinder)
    {
        $this->loopFinder = $loopFinder;
    }

    /**
     * @param $node
     * @return $this
     */
    public function setNode($node)
    {
        $this->node = $node;
        return $this;
    }

    /**
     * @return \Cms\CoreBundle\Document\Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param $nodeRepo
     * @return $this
     */
    public function setNodeRepo($nodeRepo)
    {
        $this->nodeRepo = $nodeRepo;
        $this->loopFinder->setNodeRepo($nodeRepo);
        return $this;
    }

    /**
     * @return \Cms\CoreBundle\Repository\NodeRepository
     */
    public function getNodeRepo()
    {
        return $this->nodeRepo;
    }

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param \Cms\CoreBundle\Services\NodeLoader\LoopFinder $loopFinder
     */
    public function setLoopFinder(\Cms\CoreBundle\Services\NodeLoader\LoopFinder $loopFinder)
    {
        $this->loopFinder = $loopFinder;
        return $this;
    }

    /**
     * @return \Cms\CoreBundle\Services\NodeLoader\LoopFinder
     */
    public function getLoopFinder()
    {
        return $this->loopFinder;
    }

    public function load()
    {
        $loop = array();
        if ( $this->node->getFormat() === 'loop-tag' OR $this->node->getFormat() === 'loop-category')
        {
            $loop = $this->loopFinder->find($this->node, $this->params);
        }
        return $loop;
    }

}