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
     * @param array $params
     * @return array|bool|\Doctrine\MongoDB\ArrayIterator|\Doctrine\MongoDB\Cursor|\Doctrine\MongoDB\EagerCursor|mixed|null
     * @throws \Exception
     */
    public function find(\Cms\CoreBundle\Document\Node $loopNode, array $params = array())
    {
        $category = array();
        $tags = array();
        if ( isset($params['taxonomyParent']) )
        {
            if ( $loopNode->getFormat() === 'loop-tag' )
            {
                $tags = array();
                $tags[] = $params['taxonomyParent'];
                if ( isset($params['taxonomySub']) )
                {
                    foreach ($params['taxonomySub'] as $tagKey => $tagValue)
                    {
                        $tags[] = $tagValue;
                    }
                }
            }
            else
            {
                $category['parent'] = $params['taxonomyParent'];
                if ( isset($params['taxonomySub']) )
                {
                    $category['sub'] = $params['taxonomySub'];
                }
            }
        }
        if ( isset($params['tags']) )
        {
            $tags = $params['tags'];
        }
        if ( ! isset($params['limit']) AND $defaultLimit = $loopNode->getDefaultLimit() )
        {
            $params['limit'] = $defaultLimit;
        }
        if ( ! isset($this->nodeRepo) )
        {
            throw new \Exception('nodeRepo must be set prior to calling LoopFinder::getLoopData');
        }
        return $this->nodeRepo->findByDomainAndLocaleAndContentTypeNameAndTaxonomy($loopNode->getDomain(), $loopNode->getLocale(), $loopNode->getContentTypeName(), $category, $tags, $params);
    }
    
}