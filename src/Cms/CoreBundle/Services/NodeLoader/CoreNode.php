<?php
/**
 * User: Brian Anderson
 * Date: 6/11/13
 * Time: 10:50 AM
 */

namespace Cms\CoreBundle\Services\NodeLoader;


/**
 * Class Core
 * @package Cms\CoreBundle\Services\NodeLoader
 */
class CoreNode {

    /**
     * @var \Cms\CoreBundle\Repository\NodeRepository
     */
    private $nodeRepo;

    /**
     * @var array $params
     */
    private $params;

    /**
     * @param \Cms\CoreBundle\Repository\NodeRepository
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
     * @param array $params
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
     * Load node. Returns falsey if not found
     *
     * @returns mixed
     */
    public function load()
    {
        if ( isset($this->params['locale']) )
        {
            return $this->nodeRepo->fineOneByHostAndSlugAndLocale( $this->params['host'], $this->params['slug'], $this->params['locale'] );
        }
        else
        {
            return $this->nodeRepo->findOneByHostAndSlug($this->params['host'], $this->params['slug']);
        }
    }



}