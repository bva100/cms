<?php
/**
 * User: Brian Anderson
 * Date: 6/6/13
 * Time: 11:31 AM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class ContentType
 * @package Cms\CoreBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class ContentType {

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $name;

    /**
     * @MongoDB\collection
     */
    private $formats;

    /**
     * @MongoDB\String
     */
    private $taxonomyStyle;

    /**
     * @MongoDB\Int
     */
    private $limit;

    /**
     * @MongoDB\String
     */
    private $slugPrefix;


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
     * Set name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set formats
     *
     * @param collection $formats
     * @return self
     */
    public function setFormats($formats)
    {
        $this->formats = $formats;
        return $this;
    }

    /**
     * Get formats
     *
     * @return collection $formats
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * Set taxonomyStyle
     *
     * @param string $taxonomyStyle
     * @return self
     */
    public function setTaxonomyStyle($taxonomyStyle)
    {
        $this->taxonomyStyle = $taxonomyStyle;
        return $this;
    }

    /**
     * Get taxonomyStyle
     *
     * @return string $taxonomyStyle
     */
    public function getTaxonomyStyle()
    {
        return $this->taxonomyStyle;
    }

    /**
     * Set limit
     *
     * @param int $limit
     * @return self
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Get limit
     *
     * @return int $limit
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set slugPrefix
     *
     * @param string $slugPrefix
     * @return self
     */
    public function setSlugPrefix($slugPrefix)
    {
        $this->slugPrefix = $slugPrefix;
        return $this;
    }

    /**
     * Get slugPrefix
     *
     * @return string $slugPrefix
     */
    public function getSlugPrefix()
    {
        return $this->slugPrefix;
    }

}
