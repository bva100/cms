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
 * @MongoDB\Document(collection="nodes")
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
    private $domain;

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
        $this->categories = array();
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
     * Get siteId
     *
     * @return string $siteId
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return self
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * Get domain
     *
     * @return string $domain
     */
    public function getDomain()
    {
        return $this->domain;
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
     * Set conversationIds
     *
     * @param collection $conversationIds
     * @return self
     */
    public function setConversationIds($conversationIds)
    {
        $this->conversationIds = $conversationIds;
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
     * Set fields
     *
     * @param hash $fields
     * @return self
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Get fields
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
     * Set view
     *
     * @param hash $view
     * @return self
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Get view
     *
     * @return hash $view
     */
    public function getView()
    {
        return $this->view;
    }
}
