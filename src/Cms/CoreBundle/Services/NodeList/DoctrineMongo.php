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


    public function getList()
    {
        if ( $this->node->getMetadata('type') )
        {
            $type = $this->node->getMetadata('type');
            if ( isset($type['format']) AND $type['format'] === 'loop' )
            {
                $site = $this->node->getMetadata('site');
                if ( ! isset($site['domain']) )
                {
                    return array();
                }
                $type = $this->node->getMetadata('type');
                if ( ! isset($type['name']) )
                {
                    return array();
                }
                $locale = $this->node->getMetadata('locale');
                return $this->repo->findDynamicNodesByContentTypeAndDomain($type['name'], $site['domain'], $locale);
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