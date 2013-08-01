<?php
/**
 * User: Brian Anderson
 * Date: 7/31/13
 * Time: 3:41 PM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class ThemeOrg
 * @package Cms\CoreBundle\Document
 * @MongoDB\Document(collection="themeOrgs")
 */
class ThemeOrg extends Base {

    /**
     * @MongoDB\String
     */
    private $organization;

    /**
     * @MongoDB\String
     */
    private $namespace;

    /**
     * @MongoDB\String
     */
    private $website;

    /**
     * @MongoDB\String
     */
    private $image;

    /**
     * @MongoDB\EmbedMany(targetDocument="Theme")
     */
    private $themes;

    public function __construct()
    {
        $this->themes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $organization;
    }

    public function getOrganization()
    {
        return $this->organization;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = ucfirst($namespace);
        return $this;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setWebsite($website)
    {
        $this->website = $website;
        return $website;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Add theme
     *
     * @param \Cms\CoreBundle\Document\theme $theme
     */
    public function addTheme(\Cms\CoreBundle\Document\theme $theme)
    {
        $this->themes[] = $theme;
    }

    /**
     * Remove theme
     *
     * @param \Cms\CoreBundle\Document\theme $theme
     */
    public function removeTheme(\Cms\CoreBundle\Document\theme $theme)
    {
        $this->themes->removeElement($theme);
    }

    /**
     * Get an array of theme entities associated with this themeOrg
     *
     * @return \Doctrine\Common\Collections\ArrayCollection themes
     */
    public function getThemes()
    {
        return $this->themes;
    }

    /**
     * Get a theme via id.
     * Returns a theme entity on success or void on failure
     *
     * @param $id
     * @return \Cms\CoreBundle\Document\theme $theme
     */
    public function getTheme($id)
    {
        foreach ($this->getThemes() as $theme)
        {
            if ( $theme->getId() === $id )
            {
                return $theme;
            }
        }
    }

    /**
     * Get a theme via name
     * Returns a theme entity on success or void on failure
     *
     * @param $name
     * @return mixed
     */
    public function getThemeByName($name)
    {
        foreach ($this->getThemes() as $theme)
        {
            if ( $theme->getName() === $name )
            {
                return $theme;
            }
        }
    }

}