<?php
/**
 * User: Brian Anderson
 * Date: 6/21/13
 * Time: 4:14 PM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Theme
 * @package Cms\CoreBundle\Document
 * @MongoDB\EmbeddedDocument
 */
class Theme extends Base {

    /**
     * @MongoDB\String
     */
    private $parentId;

    /**
     * @MongoDB\String
     */
    private $name;

    /**
     * @MongoDB\String
     */
    private $description;

    /**
     * @MongoDB\Boolean
     */
    private $hasComponents;

    /**
     * @MongoDB\Collection
     */
    private $layouts;

    /**
     * @MongoDB\Hash
     */
    private $images;

    public function __construct()
    {
        $this->setState('draft');
        $this->setHasComponents(false);
        $this->layouts = array('Single', 'Loop', 'Static');
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
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set bool value for has components template
     *
     * @param bool $hasComponents
     * @return $this
     */
    public function setHasComponents($hasComponents)
    {
        $this->hasComponents = $hasComponents;
        return $this;
    }

    /**
     * Check if theme has a components template
     *
     * @return bool
     */
    public function getHasComponents()
    {
        return $this->hasComponents;
    }

    /**
     * Set layouts.
     *
     * @param array $layouts
     * @return $this
     */
    public function setLayouts(array $layouts)
    {
        $this->layouts = $layouts;
        return $this;
    }

    /**
     * Add a layout type
     *
     * @param $type
     * @return $this
     */
    public function addLayout($type)
    {
        if ( ! is_string($type) )
        {
            return $this;
        }
        $key = array_search($type, $this->layouts);
        if ( $key === false )
        {
            $this->layouts[] = $type;
        }
        return $this;
    }

    /**
     * Remove a layout type
     *
     * @param $type
     * @return $this
     */
    public function removeLayout($type)
    {
        if ( ! is_string($type) )
        {
            return $this;
        }
        $key = array_search($type, $this->layouts);
        if ( $key !== false )
        {
            unset($this->layouts[$key]);
            $this->layouts = array_values($this->layouts);
        }
        return $this;
    }

    /**
     * Check if layout exists in theme
     *
     * @param $type
     * @return bool
     */
    public function hasLayout($type)
    {
        $key = array_search($type, $this->layouts);
        return $key !== false ? true : false;
    }

    /**
     * Get layouts
     *
     * @return array $layouts
     */
    public function getLayouts()
    {
        return $this->layouts;
    }

    /**
     * Set images
     *
     * @param array $images
     */
    public function setImages(array $images)
    {
        $this->images = $images;
    }

    /**
     * Get the url to a specific image (eg "featured"). Returns void if not found.
     *
     * @param featured $imageType
     * @return string
     */
    public function getImage($imageType)
    {
        if ( isset($this->images[$imageType]) )
        {
            return $this->images[$imageType];
        }
    }

    /**
     * Get an array of images
     *
     * @return array
     */
    public function getImages()
    {
        return $this->images;
    }

}
