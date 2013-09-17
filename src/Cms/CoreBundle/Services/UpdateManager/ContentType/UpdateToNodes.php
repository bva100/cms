<?php
/**
 * User: Brian Anderson
 * Date: 6/11/13
 * Time: 4:28 PM
 */

namespace Cms\CoreBundle\Services\UpdateManager\ContentType;

class UpdateToNodes {

    /**
     * @var \Cms\CoreBundle\Repository\NodeRepository
     */
    private $nodeRepo;

    /**
     * @param \Cms\CoreBundle\Repository\NodeRepository $nodeRepo
     * @return $this
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
     * Updates contentType slugPrefix prefix then propagates slug changes to all nodes associated with contentType object
     *
     * @param \Cms\CoreBundle\Document\ContentType $contentType
     * @param $newSlugPrefix
     * @return \Cms\CoreBundle\Document\ContentType
     */
    public function updateSlugPrefix(\Cms\CoreBundle\Document\ContentType $contentType, $newSlugPrefix)
    {
        if ( substr($newSlugPrefix, -1) !== '/' )
        {
            $newSlugPrefix = $newSlugPrefix.'/';
        }
        $newSlugPrefix = ltrim($newSlugPrefix, '/');

        $oldSlugPrefix = $contentType->getSlugPrefix();
        $contentType->setSlugPrefix($newSlugPrefix);
        if ( ! $oldSlugPrefix )
        {
            return $contentType;
        }

        $nodes = $this->nodeRepo->findByContentTypeName($contentType->getName());
        foreach ($nodes as $node)
        {
            $oldSlug = $node->getSlug();
            if ( $oldSlug )
            {
                // if old slug prefix was in the first position of old slug. Be sure to account for slugPrefix's trailing "/"
                if ( substr($oldSlug, -1) === '/' )
                {
                    $moddedOldSlug = substr($oldSlug, 0, -1);
                }
                else
                {
                    $moddedOldSlug = $oldSlug;
                }
                if ( strpos($moddedOldSlug, $oldSlugPrefix) === 0 )
                {
                    //replace old slug prefix with new slug prefix
                    $newSlug = str_replace($oldSlugPrefix, $newSlugPrefix, $oldSlug);
                }
                else
                {
                    // otherwise append new slug prefix to beginning of old slug
                    $newSlug = $newSlugPrefix.$oldSlug;
                }
                $node->setSlug($newSlug);
            }
        }
        return $contentType;
    }

}