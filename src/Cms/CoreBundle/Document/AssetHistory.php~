<?php
/**
 * User: Brian Anderson
 * Date: 6/24/13
 * Time: 1:00 PM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class ContentType
 * @package Cms\CoreBundle\Document
 * @MongoDB\Document(collection="assetHistory")
 */
class AssetHistory {

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $parentId;

    /**
     * @MongoDB\String
     */
    private $content;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parentId
     *
     * @param string $parentId
     * @return self
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * Get parentId
     *
     * @return string $parentId
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

}
