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
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($orgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme org with id '.$orgId.' not found');
        }
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
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($themeOrgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme Organization with id '.$themeOrgId.' not found');
        }
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
        $xmlResponse =  $this->get('xmlResponse')->execute($this->getRequest(), $success, array('onSuccess' => $theme->getId()));
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
    }

    public function componentsAction($orgId, $themeId)
    {
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($orgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme organzation with id '.$orgId.' not found');
        }
        // ensure user has access to this theme org
        $theme = $themeOrg->getTheme($themeId);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$themeId.' not found');
        }
        if ( ! $theme->getName() )
        {
            throw $this->createNotFoundException('Theme name must exist befor proceeding');
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
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($orgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme organzation with id '.$orgId.' not found');
        }
        // ensure user has access to this theme org
        $theme = $themeOrg->getTheme($themeId);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$themeId.' not found');
        }
        $layout = '';
        if ( $layoutName )
        {
            if ( ! $theme->hasLayout($layoutName) )
            {
                throw $this->createNotFoundException('Layout '.$layoutName.' not found in theme');
            }
            $layout = $this->get('persister')->getRepo('CmsCoreBundle:Template')->findOneByName($themeOrg->getNamespace().':'.$theme->getName().':'.$layoutName);
        }
        $template = $layout ? $layout->getContent() : null;
        $rawCode = $template;
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
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($orgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme organization with id '.$orgId.' not found');
        }
        // ensure user has access to this theme org
        $theme = $themeOrg->getTheme($themeId);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$themeId.' not found');
        }
        if ( ! preg_match('/^[0-9a-zA-Z]+$/', $layoutName) )
        {
            throw new \Exception('invalid layout template name');
        }
        if ( ! $actionType OR $actionType === 'add' )
        {
            $theme->addLayout($layoutName);
        }else{
            $theme->removeLayout($layoutName);
            // also remove from template collection, if it exists
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
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($themeOrgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme Org with id '.$themeId.' not found');
        }
        $theme = $themeOrg->getTheme($themeId);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$themeId.' not found');
        }
        $template = $templateId ? $this->get('persister')->getRepo('CmsCoreBundle:Template')->find($templateId) : new Template();
        if ( ! $template )
        {
            throw $this->createNotFoundException('Template with id '.$templateId.' not found');
        }
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
        $success = $this->get('persister')->save($template, false, false);
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

}