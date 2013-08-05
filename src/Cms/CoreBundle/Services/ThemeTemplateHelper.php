<?php
/**
 * User: Brian Anderson
 * Date: 8/4/13
 * Time: 9:55 PM
 */

namespace Cms\CoreBundle\Services;

use \Cms\CoreBundle\Document\Template;

/**
 * Class themeTemplateHelper
 * @package Cms\CoreBundle\Services
 */
class ThemeTemplateHelper {

    private $site;

    private $themeOrg;

    private $theme;

    /**
     * @param $site
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set theme organization
     *
     * @param $themeOrg
     * @return $this
     */
    public function setThemeOrg($themeOrg)
    {
        $this->themeOrg = $themeOrg;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getThemeOrg()
    {
        return $this->themeOrg;
    }

    /**
     * @param $theme
     * @return $this
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Get the Template name affix eg: 'Core:Master:' or "Themer:Example:"
     *
     * @return string
     */
    public function getTemplateNameAffix()
    {
        return $this->themeOrg->getNamespace().':'.$this->theme->getName().':';
    }

    /**
     * Create the child template of a theme's components template
     *
     * @return Template
     */
    public function createChildComponentsTemplate()
    {
        $nameAffix = $this->getTemplateNameAffix();
        $template = new Template();
        $template->setName($this->site->getNamespace().'-'.$nameAffix.'Components');
        $template->setThemeId($this->theme->getId());
        $template->setContent("{% extends '".$nameAffix."Components' %}");
        $template->setState('active');
        return $template;
    }

    public function createChildLayoutTemplate($layoutName)
    {
        $nameAffix = $this->getTemplateNameAffix();
        $template = new Template();
        $template->setName($this->site->getNamespace().'-'.$nameAffix.$layoutName);
        $template->setThemeId($this->theme->getId());
        $template->setContent("{% extends '".$nameAffix.$layoutName."' %}");
        $template->setState('active');
        return $template;
    }

}