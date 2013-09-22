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
class Site extends Base {

    /**
     * @MongoDB\String
     */
    private $name;

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

    /**
     * @MongoDB\Collection
     */
    private $templateNames;

    /**
     * @MongoDB\Collection
     */
    private $themes;

    /**
     * @MongoDB\Hash
     */
    private $currentTheme;

    /**
     * @MongoDB\String
     */
    private $clientSecret;

    public function __construct()
    {
        $this->created = time();
        $this->setState('active');
        $this->contentType = new \Doctrine\Common\Collections\ArrayCollection();
        $this->domains = array();
        $this->templateNames = array('Core:Base:HTML');
        $this->themes = array();
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
     * Set an array of domains
     *
     * @param array $domains
     * @return $this
     */
    public function setDomains(array $domains)
    {
        $this->domains = $domains;
        return $this;
    }

    /**
     * Add a domain
     *
     * @param $domain
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
     * @return array $domains
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * Add contentType
     *
     * @param ContentType $contentType
     * @return $this
     */
    public function addContentType(\Cms\CoreBundle\Document\ContentType $contentType)
    {
        $this->contentTypes[] = $contentType;
        return $this;
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
     * Get a contentType via name
     * Returns a contentType entity on success and void on failure
     *
     * @param $name
     * @return mixed
     */
    public function getContentTypeByName($name)
    {
        foreach ($this->getContentTypes() as $contentType)
        {
            if ( $contentType->getName() === $name )
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

    /**
     * Set template names
     *
     * @param array $templateNames
     * @return $this
     */
    public function setTemplateNames(array $templateNames)
    {
        $this->templateNames = $templateNames;
        return $this;
    }

    /**
     * Add a template name
     *
     * @param $templateName
     * @return $this
     */
    public function addTemplateName($templateName)
    {
        if ( is_string($templateName) AND ! in_array($templateName, $this->templateNames) )
        {
            $this->templateNames[] = $templateName;
        }
        return $this;
    }

    /**
     * Remove a template name
     *
     * @param $templateName
     * @return $this
     */
    public function removeTemplateName($templateName)
    {
        if ( ! is_string($templateName) )
        {
            return $this;
        }
        $key = array_search($templateName, $this->templateNames);
        if ( $key !== false )
        {
            unset($this->templateNames[$key]);
            $this->templateNames = array_values($this->templateNames);
        }
        return $this;
    }

    /**
     * Does site have access to this template name?
     *
     * @param $templateName
     * @return bool
     */
    public function hasTemplateName($templateName)
    {
        return in_array($templateName, $this->templateNames) ? true : false;
    }

    /**
     * Get template names array
     *
     * @return array
     */
    public function getTemplateNames()
    {
        return $this->templateNames;
    }

    /**
     * Set themes
     *
     * @param array $themes
     * @return $this
     */
    public function setThemes(array $themes)
    {
        $this->themes = $themes;
        return $this;
    }

    /**
     * Add a theme value object
     * Structure: id, orgId, name, img,
     *
     * @param array $theme
     * @return $this
     */
    public function addTheme(array $theme)
    {
        $key = array_search($theme, $this->themes);
        if ( $key === false )
        {
            $this->themes[] = $theme;
        }
        return $this;
    }

    /**
     * Remove a theme value object
     *
     * @param array $theme
     * @return $this
     */
    public function removeTheme(array $theme)
    {
        $key = array_search($theme, $this->themes);
        if ( $key !== false )
        {
            unset($this->themes[$key]);
            $this->themes = array_values($this->themes);
        }
        return $this;
    }

    /**
     * Remove theme by ID
     *
     * @param $id
     * @return $this
     */
    public function removeThemeById($id)
    {
        foreach ($this->themes as $theme) {
            if ( isset($theme['id']) )
            {
                if ( $theme['id'] === $id )
                {
                    return $this->removeTheme($theme);
                }
            }
        }
        return $this;
    }

    /**
     * Get theme value objects as an array
     *
     * @return array
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * Set current theme value object
     *
     * @param $orgId
     * @param $themeId
     * @return $this
     */
    public function setCurrentTheme($orgId, $themeId)
    {
        $this->currentTheme = array('orgId' => $orgId, 'themeId' => $themeId);
        return $this;
    }

    /**
     * Get current theme value object with indices of orgId and themeId
     *
     * @return array
     */
    public function getCurrentTheme()
    {
        return $this->currentTheme;
    }

    /**
     * Set client secret. Use API access token server to generate secret.
     *
     * @param $clientSecret
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * Get client secret for API usage
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

}
