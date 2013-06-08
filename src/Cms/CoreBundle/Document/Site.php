<?php
/**
 * User: Brian Anderson
 * Date: 6/6/13
 * Time: 11:11 AM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Site
 * @package Cms\CoreBundle\Document
 * @MongoDB\Document(collection="sites")
 */
class Site {

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
    private $namespace;

    /**
     * @MongoDB\String
     */
    private $domain;

    /**
     * @MongoDB\EmbedMany(targetDocument="ContentType")
     */
    private $contentTypes;

    public function __construct()
    {
        $this->contentType = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set namespace
     *
     * @param string $namespace
     * @return self
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Get namespace
     *
     * @return string $namespace
     */
    public function getNamespace()
    {
        return $this->namespace;
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
     * Add contentType
     *
     * @param Cms\CoreBundle\Document\ContentType $contentType
     */
    public function addContentType(\Cms\CoreBundle\Document\ContentType $contentType)
    {
        $this->contentTypes[] = $contentType;
    }

    /**
    * Remove contentType
    *
    * @param <variableType$contentType
    */
    public function removeContentType(\Cms\CoreBundle\Document\ContentType $contentType)
    {
        $this->contentTypes->removeElement($contentType);
    }

    /**
     * Get contentType
     *
     * @return Doctrine\Common\Collections\Collection $contentType
     */
    public function getContentTypes()
    {
        return $this->contentTypes;
    }
}
