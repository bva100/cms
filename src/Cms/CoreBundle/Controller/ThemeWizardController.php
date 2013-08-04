<?php
/**
 * User: Brian Anderson
 * Date: 8/1/13
 * Time: 11:54 AM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Theme;
use Cms\CoreBundle\Document\Template;

class ThemeWizardController extends Controller {

    public function basicAction($orgId)
    {
        $id = (string)$this->getRequest()->query->get('id');
        $themeOrg = $this->getThemeOrg($orgId);
        $theme = $id ?  $themeOrg->getTheme($id) : null;
        if ( $id AND ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$id.' not found');
        }
        return $this->render('CmsCoreBundle:Theme:wizard.html.twig', array(
            'themeOrg' => $themeOrg,
            'theme' =>  $theme,
        ));
    }

    public function saveThemeAction()
    {
        $themeOrgId = (string)$this->getRequest()->request->get('themeOrgId');
        $id = (string)$this->getRequest()->request->get('id');
        $name = (string)$this->getRequest()->request->get('name');
        $image = (string)$this->getRequest()->request->get('image');
        $description = (string)$this->getRequest()->request->get('description');
        $themeOrg = $this->getThemeOrg($themeOrgId);
        $theme = $id ? $themeOrg->getTheme($id) : new Theme(); // add id and load theme here
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$id.' not found');
        }
        if ( $name )
        {
            $theme->setName(trim($name));
        }
        if ( $image )
        {
            $theme->setImages(array(array('featured' => $image)));
        }
        if ( $description )
        {
            $theme->setDescription($description);
        }
        $themeOrg->addTheme($theme);
        $success = $this->get('persister')->save($themeOrg, false, false);
        return $this->get('xmlResponse')->execute($this->getRequest(), $success, array('onSuccess' => $theme->getId()));
    }

    public function componentsAction($orgId, $themeId)
    {
        $themeOrg = $this->getThemeOrg($orgId);
        $theme = $this->getTheme($themeOrg, $themeId);
        if ( ! $theme->getName() )
        {
            throw $this->createNotFoundException('Theme name must exist before proceeding');
        }
        $componentsName = $themeOrg->getNamespace().':'.$theme->getName().':Components';
        $template = $this->get('persister')->getRepo('CmsCoreBundle:Template')->findOneByName($componentsName);
        $rawCode = $template ? str_replace("{% set master_html = namespace ~ ':Master:HTML' %}{% extends master_html %}", '', $template->getContent()): null;
        return $this->render('CmsCoreBundle:Theme:wizardTemplate.html.twig', array(
            'themeOrg' => $themeOrg,
            'theme' => $theme,
            'extends' => 'html_master of client',
            'uses' => null,
            'template' => $template ? $template : null,
            'rawCode' => $rawCode,
            'componentsName' => $componentsName,
        ));
    }

    public function layoutsAction($orgId, $themeId)
    {
        $layoutName = (string)$this->getRequest()->query->get('layoutName');
        $themeOrg = $this->getThemeOrg($orgId);
        $theme = $this->getTheme($themeOrg, $themeId);
        $layout = '';
        if ( $layoutName )
        {
            if ( ! $theme->hasLayout($layoutName) )
            {
                throw $this->createNotFoundException('Layout '.$layoutName.' not found in theme');
            }
            $layout = $this->get('persister')->getRepo('CmsCoreBundle:Template')->findOneByName($themeOrg->getNamespace().':'.$theme->getName().':'.$layoutName);
        }
        if ( $layout )
        {
            $template = $layout;
            $components = $themeOrg->getNamespace().':'.$theme->getName().':Components';
            $rawCode = str_replace("{% set ".$themeOrg->getNamespace()."_".$theme->getName()."_components = namespace ~ '-".$components."' %}{% extends ".$themeOrg->getNamespace()."_".$theme->getName()."_components %}", '', $template->getContent());
        }else{
            $template = null;
            $rawCode = null;
        }
        $layouts = $theme->getLayouts();
        return $this->render('CmsCoreBundle:Theme:wizardTemplate.html.twig', array(
            'themeOrg' => $themeOrg,
            'theme' => $theme,
            'extends' => 'Client\'s Child of Components',
            'uses' => null,
            'template' => $template,
            'rawCode' => $rawCode,
            'layouts' => $layouts,
            'layoutName' => $layoutName ? $layoutName : null,
        ));
    }

    public function addRemoveLayoutAction($actionType)
    {
        $orgId = (string)$this->getRequest()->request->get('themeOrgId');
        $themeId = (string)$this->getRequest()->request->get('themeId');
        $layoutName = $this->getRequest()->request->get('layoutName');
        $themeOrg = $this->getThemeOrg($orgId);
        // ensure user has access to this theme org
        $theme = $themeOrg->getTheme($themeId);
        if ( ! preg_match('/^[0-9a-zA-Z]+$/', $layoutName) )
        {
            throw new \Exception('invalid layout template name');
        }
        if ( ! $actionType OR $actionType === 'add' )
        {
            $theme->addLayout($layoutName);
        }else{
            if ( $layoutName !== 'Single' AND $layoutName !== 'Static' AND $layoutName !== 'Loop') 
            {
                $theme->removeLayout($layoutName);
                $template = $this->get('persister')->getRepo('CmsCoreBundle:Template')->findOneByName($themeOrg->getNamespace().':'.$theme->getName().':'.$layoutName);
                if ( $template )
                {
                    $this->get('persister')->delete($template);
                }
            }
        }
        $success = $this->get('persister')->save($themeOrg);
        return $this->get('xmlResponse')->execute($this->getRequest(), $success);
    }

    public function saveComponentsAction()
    {
        $templateId = (string)$this->getRequest()->request->get('templateId');
        $rawCode = $this->getRequest()->request->get('rawCode');
        $uses = json_decode((string)$this->getRequest()->request->get('uses')); // add for BETA
        $themeOrgId = (string)$this->getRequest()->request->get('themeOrgId');
        $themeId = (string)$this->getRequest()->request->get('themeId');
        $themeOrg = $this->getThemeOrg($themeOrgId);
        $theme = $this->getTheme($themeOrg, $themeId);
        $template = $this->getTemplate($templateId);
        $template->setThemeId($themeId);
        if ( $rawCode )
        {
            $name = $themeOrg->getNamespace().':'.$theme->getName().':Components';
            $template->setName($name);
            if ( strpos($rawCode, "{% set master_html = namespace ~ ':Master:HTML' %}{% extends master_html %}") !== true )
            {
                $rawCode = "{% set master_html = namespace ~ ':Master:HTML' %}{% extends master_html %}".$rawCode;
            }
            $template->setContent($rawCode);
        }
        $success = $this->get('persister')->save($template);
        if ( ! $success )
        {
            throw new \Exception('Unable to save at this time. Please try again soon');
        }
        $theme->setHasComponents(true);
        $success = $this->get('persister')->save($themeOrg, false, false);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.template_readAll'));
    }

    public function saveLayoutAction()
    {
        $templateId = (string)$this->getRequest()->request->get('templateId');
        $layoutName = (string)$this->getRequest()->request->get('layoutName');
        $rawCode = $this->getRequest()->request->get('rawCode');
        $uses = json_decode((string)$this->getRequest()->request->get('uses')); // add for BETA
        $themeOrgId = (string)$this->getRequest()->request->get('themeOrgId');
        $themeId = (string)$this->getRequest()->request->get('themeId');
        $themeOrg = $this->getThemeOrg($themeOrgId);
        $theme = $this->getTheme($themeOrg, $themeId);
        $template = $this->getTemplate($templateId);
        $template->setThemeId($themeId);
        if ( $rawCode )
        {
            if ( ! preg_match('/^[0-9a-zA-Z]+$/', $layoutName) )
            {
                throw new \Exception('invalid layout template name');
            }
            $name = $themeOrg->getNamespace().':'.$theme->getName().':'.$layoutName;
            $components = $themeOrg->getNamespace().':'.$theme->getName().':Components';
            $template->setName($name);
            if ( strpos($rawCode, "{% set ".$themeOrg->getNamespace()."_".$theme->getName()."_components = namespace ~ '-".$components."' %}{% extends ".$themeOrg->getNamespace()."_".$theme->getName()."_components %}") !== true )
            {
                $rawCode = "{% set ".$themeOrg->getNamespace()."_".$theme->getName()."_components = namespace ~ '-".$components."' %}{% extends ".$themeOrg->getNamespace()."_".$theme->getName()."_components %}".$rawCode;
            }
            $template->setContent($rawCode);
        }
        $success = $this->get('persister')->save($template);
        return $this->get('xmlResponse')->execute($this->getRequest(), $success);
    }

    public function completeAction($orgId, $themeId)
    {
        $themeOrg = $this->getThemeOrg($orgId);
        $theme = $this->getTheme($themeOrg, $themeId);
        return $this->render('CmsCoreBundle:Theme:wizardComplete.html.twig', array(
            'themeOrg' => $themeOrg,
            'theme' => $theme,
        ));
    }

    public function getThemeOrg($themeOrgId)
    {
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($themeOrgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme Organization with id '.$orgId.' not found');
        }
        return $themeOrg;
    }

    public function getTheme($themeOrg, $themeId)
    {
        $theme = $themeOrg->getTheme($themeId);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$themeId.' not found');
        }
        return $theme;
    }

    public function getTemplate($templateId)
    {
        $template = $templateId ? $this->get('persister')->getRepo('CmsCoreBundle:Template')->find($templateId) : new Template();
        if ( ! $template )
        {
            throw $this->createNotFoundException('Template with id '.$templateId.' not found');
        }
        return $template;
    }
    
}