<?php
/**
 * User: Brian Anderson
 * Date: 6/7/13
 * Time: 9:56 PM
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
     * @MongoDB\String @MongoDB\Index(unique=true)
     */
    private $name;

    /**
     * @MongoDB\Collection
     */
    private $formats;

    /**
     * @MongoDB\String
     */
    private $taxonomy;

    /**
     * @MongoDB\String
     */
    private $slugPrefix;

    /**
     * @MongoDB\Hash
     */
    private $categories;

    public function __construct()
    {
        $this->categories = array();
    }

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
     * Set taxonomy
     *
     * @param string $taxonomy
     * @return self
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return string $taxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
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

    /**
     * Set categories
     *
     * @param array $categories
     * @return self
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * Get categories
     *
     * @return array $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
