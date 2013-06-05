<?php
/**
 * User: Brian Anderson
 * Date: 6/5/13
 * Time: 12:03 PM
 */

namespace Cms\CoreBundle\Services\NodeList;


/**
 * Class DoctrineMongo
 * @package Cms\CoreBundle\Services\NodeList
 */
class DoctrineMongo {

    /**
     * @var a Doctrine Mongo node repo
     */
    private $repo;

    /**
     * @var \Cms\CoreBundle\Document\Node
     */
    private $node;

    /**
     * @param $em
     */
    public function __construct($em)
    {
        $this->repo = $em->getRepository('CmsCoreBundle:Node');
    }

    /**
     * @param \Cms\CoreBundle\Document\Node $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    /**
     * @return \Cms\CoreBundle\Document\Node
     */
    public function getNode()
    {
        return $this->node;
    }


    public function getList()
    {
        if ( $this->node->getMetadata('type') )
        {
            $type = $this->node->getMetadata('type');
            if ( isset($type['format']) AND $type['format'] === 'loop' )
            {
                $site = $this->node->getMetadata('site');
                $type = $this->node->getMetadata('type');
                $query =
            }
            else
            {
                return array();
            }
        }
        else
        {
            return array();
        }
    }



}