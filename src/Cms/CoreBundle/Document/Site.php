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
 * @MongoDB\Document(collection="sites", repositoryClass="Cms\CoreBundle\Repository\SiteRepository")
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
     * @MongoDB\Collection
     */
    private $domains;

    /**
     * @MongoDB\EmbedMany(targetDocument="ContentType")
     */
    private $contentTypes;

    public function __construct()
    {
        $this->setState('active');
        $this->contentType = new \Doctrine\Common\Collections\ArrayCollection();
        $this->domains = array();
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
     * Not used
     *
     * @throws \Exception
     */
    public function setDomains()
    {
        throw new \Exception('setDomains not used. Please use addDomain instead');
    }

    /**
     * Add a domain
     *
     * @param string $domain
     * @return $this
     */
    public function addDomain($domain)
    {
        if ( is_string($domain) AND ! in_array($domain, $this->domains) )
        {
            $this->domains[] = $domain;
        }
        return $this;
    }

    /**
     * Remove a domain
     *
     * @param string $domain
     * @return $this
     */
    public function removeDomain($domain)
    {
        if ( ! is_string($domain) )
        {
            return $this;
        }
        $key = array_search($domain, $this->domains);
        if ( $key !== false )
        {
            unset($this->domains[$key]);
            $this->domains = array_values($this->domains);
        }
        return $this;
    }

    /**
     * Get domains
     *
     * @return collection $domains
     */
    public function getDomains()
    {
        return $this->domains;
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
     * Get a contentType via id.
     * Returns a contentType entity on success and void on failure
     *
     * @param $id
     * @return mixed
     */
    public function getContentType($id)
    {
        foreach ($this->getContentTypes() as $contentType)
        {
            if ( $contentType->getId() === $id )
            {
                return $contentType;
            }
        }
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
