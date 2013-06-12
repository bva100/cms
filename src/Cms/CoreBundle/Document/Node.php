<?php
/**
 * User: Brian Anderson
 * Date: 6/7/13
 * Time: 10:11 PM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Node
 * @package Cms\CoreBundle\Document
 * @MongoDB\Document(collection="nodes", repositoryClass="Cms\CoreBundle\Repository\NodeRepository")
 */
class Node {

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\String
     */
    private $state;

    /**
     * @MongoDB\String
     */
    private $siteId;

    /**
     * @MongoDB\String
     */
    private $host;

    /**
     * @MongoDB\String
     */
    private $contentTypeName;

    /**
     * @MongoDB\String
     */
    private $format;

    /**
     * @MongoDB\String
     */
    private $locale;

    /**
     * @MongoDB\Collection
     */
    private $categories;

    /**
     * @MongoDB\Collection
     */
    private $tags;

    /**
     * @MongoDB\Int
     */
    private $defaultLimit;

    /**
     * @MongoDB\String @MongoDB\Index
     */
    private $slug;

    /**
     * @MongoDB\String
     */
    private $title;

    /**
     * @MongoDB\String
     */
    private $templateName;

    /**
     * @MongoDB\Collection
     */
    private $conversationIds;

    /**
     * @MongoDB\Hash
     */
    private $fields;

    /**
     * @MongoDB\Hash
     */
    private $author;

    /**
     * @MongoDB\Hash
     */
    private $view;

    public function __construct()
    {
        $this->setState('active');
        $this->categories = array();
        $this->conversationIds = array();
        $this->tags = array();
        $this->fields = array();
        $this->author = array();
        $this->view = array();
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
     * Set siteId
     *
     * @param string $siteId
     * @return self
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        return $this;
    }

    /**
     * Set host
     *
     * @param string $host
     * @return self
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Get host
     *
     * @return string $host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get siteId
     *
     * @return string $siteId
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set contentTypeName
     *
     * @param string $contentTypeName
     * @return self
     */
    public function setContentTypeName($contentTypeName)
    {
        $this->contentTypeName = $contentTypeName;
        return $this;
    }

    /**
     * Get contentTypeName
     *
     * @return string $contentTypeName
     */
    public function getContentTypeName()
    {
        return $this->contentTypeName;
    }

    /**
     * Set format ('static', 'single', 'loop')
     *
     * @param string $format
     * @return self
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Get format
     *
     * @return string $format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set locale
     *
     * @param string $locale
     * @return self
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Get locale
     *
     * @return string $locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Not used
     *
     * @throws \Exception
     */
    public function setCategories()
    {
        throw new \Exception('Please use the addCategory method instead of setCategories');
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
     * Remove Category
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
     * @return hash $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Not used
     *
     * @throws \Exception
     */
    public function setTags()
    {
        throw new \Exception('Please use the addTag method instead of setTags');
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
     * Set defaultLimit
     *
     * @param int $defaultLimit
     * @return self
     */
    public function setDefaultLimit($defaultLimit)
    {
        $this->defaultLimit = $defaultLimit;
        return $this;
    }

    /**
     * Get defaultLimit
     *
     * @return int $defaultLimit
     */
    public function getDefaultLimit()
    {
        return $this->defaultLimit;
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
     * Set slug
     *
     * @param string $slug
     * @return self
     */
    public function setSlug($slug)
    {
        $slug = ltrim($slug, '/');
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
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


    /**
     * Not used
     *
     * @throws \Exception
     */
    public function setConversationIds()
    {
        throw new \Exception('setConversationIds is not used. Please call the addConversationId instead');
    }

    /**
     * Add a conversation id
     *
     * @param $id
     * @return $this
     */
    public function addConversationId($id)
    {
        if ( in_array($id, $this->conversationIds) )
        {
            return $this;
        }
        $this->conversationIds[] = $id;
        return $this;
    }

    public function removeConversationId($id)
    {
        $keys = \array_keys($this->conversationIds, $id);
        foreach ($keys as $key)
        {
            unset($this->conversationIds[$key]);
            $this->conversationIds = array_values($this->conversationIds);
        }
        return $this;
    }

    /**
     * Get conversationIds
     *
     * @return collection $conversationIds
     */
    public function getConversationIds()
    {
        return $this->conversationIds;
    }

    /**
     * Not used
     *
     * @throws \Exception
     */
    public function setFields()
    {
        throw new \Exception('setFields method is not used. Please use the addField method insetead.');
    }

    /**
     * Only also for (string) key (string\int) value pairs
     * Cannot have more than one value for the same key, new value overrides old value whens ame key is passed
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function addField($key, $value)
    {
        if ( ! is_string($key) )
        {
            return $this;
        }
        if ( ! is_string($value) AND ! is_int($value) )
        {
            return $this;
        }
        $this->fields[$key] = $value;
        return $this;
    }

    /**
     * Remove a field
     *
     * @param $key
     */
    public function removeField($key)
    {
        if ( \is_string($key) )
        {
            unset($this->fields[$key]);
        }
    }

    /**
     * Get field associated with a specfic key
     *
     * @param $key
     * @return null
     */
    public function getField($key)
    {
        if ( is_string($key) )
        {
            return isset($this->fields[$key]) ? $this->fields[$key] : null;
        }
    }

    /**
     * Get all fields as an array of key value pairs
     *
     * @return hash $fields
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set author
     *
     * @param hash $author
     * @return self
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     *
     * @return hash $author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Not used
     *
     * @throws \Exception
     */
    public function setView()
    {
        throw new \Exception('setView method is not used. Please use the addView method instead.');
    }

    /**
     * Add a new view. Does not allow for duplicates.
     *
     * @param $format
     * @param $value
     * @return $this
     */
    public function addView($format, $value)
    {
        if ( ! is_string($format) OR ! is_string($value) )
        {
            return $this;
        }
        $this->view[$format] = $value;
    }

    /**
     * Get a specific view value assocaited with a format
     *
     * @return hash $view
     */
    public function getView($format)
    {
        if ( is_string($format)  )
        {
            return isset($this->view[$format]) ? $this->view[$format] : null;
        }
    }

    /**
     * Get all view formats as an array of format value pairs
     *
     * @return array
     */
    public function getViews()
    {
        return $this->view;
    }
    
}
