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
class ContentType extends Base {

    /**
     * @MongoDB\String @MongoDB\Index(unique=true)
     */
    private $name;

    /**
     * @MongoDB\String
     */
    private $description;

    /**
     * @MongoDB\Collection
     */
    private $formats;

    /**
     * @MongoDB\Collection
     */
    private $loopSlugs;

    /**
     * Used to override default template. Not yet implemented.
     *
     * @MongoDB\String
     */
    private $templateName;

    /**
     * @MongoDB\String
     */
    private $slugPrefix;

    /**
     * @MongoDB\Hash
     */
    private $categories;

    /**
     * @MongoDB\Collection
     */
    private $tags;

    /**
     * @MongoDB\Collection
     */
    private $fields;

    public function __construct()
    {
        $this->formats = array();
        $this->loopSlugs = array();
        $this->categories = array();
        $this->fields = array();
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
     * Set Description
     *
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set formats
     *
     * @param array $formats
     * @return $this
     */
    public function setFormats(array $formats)
    {
        $this->formats = $formats;
        return $this;
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
     * Set loopSlugs. Collection of loop slugs.
     *
     * @param array $loopSlug
     * @return $this
     */
    public function setLoops(array $loopSlug)
    {
        $this->loopSlugs = $loopSlug;
        return $this;
    }

    /**
     * Add a loop slug to the loop collection.
     *
     * @param $loopSlug
     * @return $this
     */
    public function addLoopSlug($loopSlug)
    {
        if ( ! is_string($loopSlug) OR in_array($loopSlug, $this->loopSlugs) )
        {
            return $this;
        }
        $this->loopSlugs[] = $loopSlug;
        return $this;
    }

    /**
     * Remove a loop slug from the loopSlugs collection.
     *
     * @param $loopSlug
     * @return $this
     */
    public function removeLoopSlug($loopSlug)
    {
        if ( ! is_string($loopSlug) )
        {
            return $this;
        }
        $keys = \array_keys($this->loopSlugs, $loopSlug);
        foreach ($keys as $key)
        {
            unset($this->loopSlugs[$key]);
            $this->loopSlugs = array_values($this->loopSlugs);
        }
        return $this;
    }

    /**
     * Get loopSlugs. Collection of loop slugs.
     *
     * @return array
     */
    public function getLoopSlugs()
    {
        return $this->loopSlugs;
    }

    /**
     * Set slugPrefix
     *
     * @param string $slugPrefix
     * @return self
     */
    public function setSlugPrefix($slugPrefix)
    {
        if ( substr($slugPrefix, -1) !== '/' )
        {
            $slugPrefix = $slugPrefix.'/';
        }
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
     * Add a new category. Does not allow duplicates. Can force addition of parent category by setting third parameter to true
     *
     * @param $parent
     * @param null $sub
     * @param bool $forceParent
     * @return $this
     */
    public function addCategory($parent, $sub = null, $forceParent = false)
    {
        if ( ! \is_string($parent) )
        {
            return $this;
        }
        $newCategory = array();
        $newCategory['parent'] = strtolower($parent);
        if ( isset($sub) AND \is_string($sub) )
        {
            $newCategory['sub'] = strtolower($sub);
        }
        if ( $forceParent AND isset($newCategory['sub']) )
        {
            if ( ! \in_array(array('parent' => $newCategory['parent']), $this->categories) )
            {
                $this->categories[] = array('parent' => $newCategory['parent']);
            }
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
     * remove a category
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
        foreach ($this->categories as $categoryKey => $categoryArray)
        {
            if ( $categoryArray === $remove )
            {
                unset($this->categories[$categoryKey]);
            }
        }
        return $this;
    }

    /**
     * Remove all categories
     *
     * @return $this
     */
    public function removeAllCategories()
    {
        $this->categories = array();
        return $this;
    }

    /**
     * Get categories
     *
     * @param bool sort
     * @return array $categories
     */
    public function getCategories($sort = true)
    {
        $categories = $this->categories;
        if ( $sort )
        {
            array_multisort($categories);
        }
        return $categories;
    }

    /**
     * Not used
     *
     * @throws \Exception
     */
    public function setFields()
    {
        throw new \Exception('setFields not used. Please use addField');
    }

    /**
     * Add a tag
     *
     * @param $tag
     * @return $this
     */
    public function addTag($tag)
    {
        if ( ! \is_string($tag) OR in_array($tag, $this->tags) )
        {
            return $this;
        }
        $this->tags[] = $tag;
        return $this;
    }

    public function removeTag($tag)
    {
        if ( ! \is_string($tag) )
        {
            return $this;
        }
        $keys = \array_keys($this->tags, $tag);
        foreach ($keys as $key)
        {
            unset($this->tags[$key]);
            $this->tags = array_values($this->tags);
        }
        return $this;
    }

    /**
     * Get tags
     *
     * @return collection $tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add a field
     *
     * @param $field
     * @return $this
     */
    public function addField($field)
    {
        if ( is_string($field) )
        {
            $key = array_search($field, $this->fields);
            if ( $key === false )
            {
                $this->fields[] = $field;
            }
        }
        return $this;
    }

    /**
     * Remove a field
     *
     * @param $field
     * @return $this
     */
    public function removeField($field)
    {
        $key = array_search($field, $this->fields);
        if ( $key !== false )
        {
            unset($this->fields[$key]);
            $this->fields = array_values($this->fields);
        }
        return $this;
    }
    
    /**
     * Get fields
     *
     * @return collection $fields
     */
    public function getFields()
    {
        return $this->fields;
    }


    /**
     * Set templateName
     *
     * @param string $templateName
     * @return self
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
        return $this;
    }

    /**
     * Get templateName
     *
     * @return string $templateName
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

}
