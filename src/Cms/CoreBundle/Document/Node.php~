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
     * @MongoDB\String
     */
    private $parentNodeId;

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
     * @MongoDB\Collection
     */
    private $javascripts;

    /**
     * @MongoDB\Collection
     */
    private $stylesheets;

    /**
     * @MongoDB\Collection
     */
    private $metatags;

    /**
     * @MongoDB\String
     */
    private $image;

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
        $this->javascripts = array();
        $this->stylesheets = array();
        $this->metatags = array();
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
     * Set parentNodeId
     *
     * @param string $parentNodeId
     * @return self
     */
    public function setParentNodeId($parentNodeId)
    {
        $this->parentNodeId = $parentNodeId;
        return $this;
    }

    /**
     * Get parentNodeId
     *
     * @return string $parentNodeId
     */
    public function getParentNodeId()
    {
        return $this->parentNodeId;
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
     * Get tags
     *
     * @return collection $tags
     */
    public function getTags()
    {
        return $this->tags;
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
     * Cannot have more than one value for the same key, new value overrides old value when names have key is passed
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
     * @return mixed
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
    public function setJavascripts()
    {
        throw new \Exception('setJavascript is not used. Pleas use the addJavascript method');
    }

    /**
     * Add a javascript
     *
     * @param $src
     * @return $this
     */
    public function addJavascript($src)
    {
        if ( is_string($src) AND ! in_array($src, $this->javascripts))
        {
            $this->javascripts[] = $src;
        }
        return $this;
    }

    /**
     * Remove a javascript
     *
     * @param $src
     * @return $this
     */
    public function removeJavascript($src)
    {
        $key = array_search($src, $this->javascripts);
        if ( $key !== false )
        {
            unset($this->javascripts[$key]);
            $this->javascripts = array_values($this->javascripts);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getJavascripts()
    {
        return $this->javascripts;
    }

    /**
     * Not used
     *
     * @throws \Exception
     */
    public function setStylesheets()
    {
        throw new \Exception('setStylesheet method not used. Please use the addStylesheet method instead.');
    }

    /**
     * Add a stylesheet
     *
     * @param $src
     * @return $this
     */
    public function addStylesheet($src)
    {
        if ( is_string($src) AND ! in_array($src, $this->stylesheets))
        {
            $this->stylesheets[] = $src;
        }
        return $this;
    }

    /**
     * @param $src
     * @return $this
     */
    public function removeStylesheet($src)
    {
        $key = array_search($src, $this->stylesheets);
        if ( $key !== false )
        {
            unset($this->stylesheets[$key]);
            $this->stylesheets = array_values($this->stylesheets);
        }
        return $this;
    }

    /**
     * Get stylesheets
     *
     * @return collection $stylesheets
     */
    public function getStylesheets()
    {
        return $this->stylesheets;
    }

    /**
     * Not used
     *
     * @throws \Exception
     */
    public function setMetatags()
    {
        throw new \Exception('setMetatags not used. Please use addMetatag');
    }

    /**
     * Adds a metatag with given attributes
     *
     * @param array $attributes
     * @return self
     */
    public function addMetatag(array $attributes)
    {
        foreach ($attributes as $attrKey => $attrValue) {
            if ( is_string($attrKey) AND is_string($attrValue) )
            {
                $validAttributes[$attrKey] = $attrValue;
            }
        }
        if ( isset($validAttributes) and ! in_array($validAttributes, $this->metatags) )
        {
            $this->metatags[] = $validAttributes;
        }
        return $this;
    }

    /**
     * Remove a metatag matching the specific attributes passed
     *
     * @param array $attributes
     * @return self
     */
    public function removeMetatag(array $attributes)
    {
        $key = array_search($attributes, $this->metatags);
        if ( $key !== false )
        {
            unset($this->metatags[$key]);
            $this->metatags = array_values($this->metatags);
        }
        return $this;
    }
    
    /**
     * Get metatags
     *
     * @return collection $metatags
     */
    public function getMetatags()
    {
        return $this->metatags;
    }

    /**
     * Set image url
     *
     * @param string $image
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return string $image
     */
    public function getImage()
    {
        return $this->image;
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
