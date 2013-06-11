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
     * @MongoDB\String
     */
    private $state;

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
    private $slugPrefix;

    /**
     * @MongoDB\Hash
     */
    private $categories;

    public function __construct()
    {
        $this->formats = array();
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
     * Set state
     *
     * @param string $state
     * @return self
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get state
     *
     * @return string $state
     */
    public function getState()
    {
        return $this->state;
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
     * Not used
     *
     * @throws \Exception
     */
    public function setFormats()
    {
        throw new \Exception('setFormats is not used. Please use addFormat instead');
    }

    /**
     * Add a new format
     *
     * @param $format
     * @return $this
     */
    public function addFormat($format)
    {
        if ( ! is_string($format) OR in_array($format, $this->formats) )
        {
            return $this;
        }
        $this->formats[] = $format;
        return $this;
    }

    public function removeFormat($format)
    {
        if ( ! is_string($format) )
        {
            return $this;
        }
        $keys = \array_keys($this->formats, $format);
        foreach ($keys as $key)
        {
            unset($this->formats[$key]);
            $this->formats = array_values($this->formats);
        }
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
     *  Not used
     *
     * @throws \Exception
     */
    public function setCategories()
    {
        throw new \Exception('setCategories is not used. Try using addCategory instead.');
    }

    /**
     * Add a new category. Does not allow duplicates.
     *
     * @param $parent
     * @param null $sub
     * @return $this
     */
    public function addCategory($parent, $sub = null)
    {
        if ( ! \is_string($parent) )
        {
            return $this;
        }
        $newCategory = array();
        $newCategory['parent'] = $parent;
        if ( isset($sub) AND \is_string($sub) )
        {
            $newCategory['sub'] = $sub;
        }
        foreach ($this->categories as $categoryKey => $categoryArray)
        {
            if ( $categoryArray === $newCategory )
            {
                return $this;
            }
        }
        $this->categories[] = $newCategory;
        return $this;
    }

    /**
     * Remove category
     *
     * @param $parent
     * @param null $sub
     * @return $this
     */
    public function removeCategory($parent, $sub = null)
    {
        if ( ! \is_string($parent) )
        {
            return $this;
        }

        $remove = array();
        $remove['parent'] = $parent;
        if ( isset($sub) AND is_string($sub) )
        {
            $remove['sub'] = $sub;
        }
        foreach ($this->getCategories() as $categoryKey => $categoryArray)
        {
            if ( $categoryArray === $remove )
            {
                unset($this->categories[$categoryKey]);
            }
        }
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
