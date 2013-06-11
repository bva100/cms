<?php
/**
 * User: Brian Anderson
 * Date: 6/10/13
 * Time: 2:26 PM
 */

namespace Cms\CoreBundle\Services\NodeLoader;

/**
 * Class LoopFinder
 * @package Cms\CoreBundle\Services\NodeLoader
 */
class LoopFinder {

    /**
     * @var \Cms\CoreBundle\Repository\NodeRepository
     */
    private $nodeRepo;

    /**
     * @param \Cms\CoreBundle\Repository\NodeRepository $nodeRepo
     */
    public function setNodeRepo(\Cms\CoreBundle\Repository\NodeRepository $nodeRepo)
    {
        $this->nodeRepo = $nodeRepo;
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
     * Get loop data for a loop node
     *
     * @param \Cms\CoreBundle\Document\Node $loopNode
     * @param array $params (string)offset, (string)limit
     * @throws \Exception
     */
    public function find(\Cms\CoreBundle\Document\Node $loopNode, array $params = array())
    {
        $category = array();
        $tags = array();
        if ( isset($params['taxonomy']) )
        {
            if ( $loopNode->getFormat() === 'loop-tag' )
            {
                $tags = $params['taxonomy'];
            }
            else if( $loopNode->getFormat() === 'loop-category' )
            {
                $category = $params['taxonomy'];
            }
        }
        if ( ! isset($params['limit']) AND $defaultLimit = $loopNode->getDefaultLimit() )
        {
            $params['limit'] = $defaultLimit;
        }
        if ( ! isset($this->nodeRepo) )
        {
            throw new \Exception('nodeRepo must be set prior to calling LoopFinder::getLoopData');
        }
        return $this->nodeRepo->findByHostAndLocaleAndContentTypeNameAndTaxonomy($loopNode->getHost(), $loopNode->getLocale(), $loopNode->getContentTypeName(), $category, $tags, $params);
    }
    
}