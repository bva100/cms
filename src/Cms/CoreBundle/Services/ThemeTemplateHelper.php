<?php
/**
 * User: Brian Anderson
 * Date: 8/4/13
 * Time: 9:55 PM
 */

namespace Cms\CoreBundle\Services;

use \Cms\CoreBundle\Document\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class themeTemplateHelper
 * @package Cms\CoreBundle\Services
 */
class ThemeTemplateHelper {

    private $site;

    private $themeOrg;

    private $theme;

    private $persister;

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
     * Set persister
     *
     * @param $persister
     * @return $this
     */
    public function setPersister($persister)
    {
        $this->persister = $persister;
        return $this;
    }

    /**
     * Get persister
     *
     * @return mixed
     */
    public function getPersister()
    {
        return $this->persister;
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

    /**
     * Create the child template of a theme's layout
     *
     * @param $layoutName
     * @return Template
     */
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

    /**
     * Create a master template for a layout
     *
     * @param $masterTemplateName
     * @param $themeTemplateName
     * @return Template
     */
    public function createMasterTemplate($masterTemplateName, $themeTemplateName)
    {
        $template = new Template();
        $template->setName($masterTemplateName);
        $template->setThemeId($this->theme->getId());
        $template->setContent("{% extends '".$themeTemplateName."' %}");
        $template->setState('active');
        return $template;
    }

    /**
     * Checks for existence of template before saving
     *
     * @param Template $template
     * @param array $params
     * @return bool
     */
    public function saveTemplate(Template $template, array $params = array())
    {
        $current = $this->persister->getRepo('CmsCoreBundle:Template')->findOneByName($template->getName());
        if ( $current )
        {
            return true;
        }
        else
        {
            return $this->persister->save($template);
        }
    }

    /**
     * Save a master templates. updates if template exists and creates if template does not.
     *
     * @return mixed
     */
    public function saveMasterTemplates()
    {
        foreach ($this->theme->getLayouts() as $layoutName) {
            $masterTemplateName = $this->site->getNamespace().':Master:'.$layoutName;
            $themeTemplateName = $this->getTemplateNameAffix().$layoutName;
            $template = $this->persister->getRepo('CmsCoreBundle:Template')->findOneByName($masterTemplateName);
            if ( $template )
            {
                $template->setContent("{% extends '".$themeTemplateName."' %}");
            }
            else
            {
                $template = $this->createMasterTemplate($masterTemplateName, $themeTemplateName);
            }
            $success = $this->persister->save($template);
            if ( ! $success )
            {
                return false;
            }
        }
        return true;
    }

}