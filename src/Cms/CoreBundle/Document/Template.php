<?php
/**
 * User: Brian Anderson
 * Date: 6/2/13
 * Time: 12:07 AM
 */

namespace Cms\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Template
 * @package Cms\CoreBundle\Document
 * @MongoDB\Document(collection="templates", repositoryClass="Cms\CoreBundle\Repository\TemplateRepository")
 */
class Template extends Base {

    /**
     * @MongoDB\String
     */
    private $name;

    /**
     * name of parent template
     * @MongoDB\String
     */
    private $parent;

    /**
     * @MongoDB\String
     */
    private $content;

    /**
     * @MongoDB\String
     */
    private $themeId;

    public function __construct()
    {
        $this->setCreated(time());
        $this->setState('active');
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
     * Set parent
     *
     * @param string $parent
     * @return self
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return string $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set theme Id
     *
     * @param string $themeId
     * @return $this
     */
    public function setThemeId($themeId)
    {
        $this->themeId = $themeId;
        return $this;
    }

    /**
     * Get themeId
     *
     * @return string
     */
    public function getThemeId()
    {
        return $this->themeId;
    }
    
}
