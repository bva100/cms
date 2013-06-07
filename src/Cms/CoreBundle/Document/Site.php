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
    private $name;

    /**
     * @MongoDB\String @MongoDB\Index(unique=true)
     */
    private $namespace;

    /**
     * @MongoDB\String
     */
    private $domain;

    /**
     * @MongoDB\String
     */
    private $templateName;

    /**
     * @MongoDB\EmbedMany(targetDocument="ContentType")
     */
    private $contentType;

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
     * Add contentType
     *
     * @param Cms\CoreBundle\Document\ContentType $contentType
     */
    public function addContentType(\Cms\CoreBundle\Document\ContentType $contentType)
    {
        $this->contentType[] = $contentType;
    }

    /**
     * Remove contentType
     *
     * @param <variableType$contentType
     */
    public function removeContentType(\Cms\CoreBundle\Document\ContentType $contentType)
    {
        $this->contentType->removeElement($contentType);
    }

    /**
     * Get contentType
     *
     * @return Doctrine\Common\Collections\Collection $contentType
     */
    public function getContentTypes()
    {
        return $this->contentType;
    }

}
