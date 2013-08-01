<?php
/**
 * User: Brian Anderson
 * Date: 6/18/13
 * Time: 3:59 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Template;

class TemplateController extends Controller {

    public function readAllAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $templates = $this->get('persister')->getRepo('CmsCoreBundle:Template')->findAll();
        return $this->render('CmsCoreBundle:Template:readAll.html.twig', array(
            'token' => $token,
            'templates' => $templates,
        ));
    }

    public function readAction($id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $template = $this->get('persister')->getRepo('CmsCoreBundle:Template')->find($id);
        if ( ! $template )
        {
            throw $this->createNotFoundException('Template with id '.$id.' not found');
        }
        return $this->render('CmsCoreBundle:Template:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'template' => $template,
        ));
    }

    public function saveComponentsAction()
    {
        $id = (string)$this->getRequest()->request->get('id');
        $themeName = (string)$this->getRequest()->request->get('themeName');
        $rawCode = $this->getRequest()->request->get('rawCode');
        $uses = json_decode((string)$this->getRequest()->request->get('uses')); // add for BETA
        $themeOrgId = (string)$this->getRequest()->request->get('themeOrgId');
        $themeId = (string)$this->getRequest()->request->get('themeId');
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($themeOrgId);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme Org with ID'.$id.' not found');
        }
        $theme = $themeOrg->getTheme($themeId);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$themeId.' not found');
        }
        $template = $id ? $this->get('persister')->getRepo('CmsCoreBundle:Template')->find($id) : new Template();
        if ( ! $template )
        {
            throw $this->createNotFoundException('Template with id '.$id.' not found');
        }
        $template->setThemeId($themeId);
        if ( $themeName )
        {
            $name = $themeOrg->getNamespace().':'.$themeName.':Components';
            $template->setName($name);
        }
        if ( $rawCode )
        {
            if ( strpos($rawCode, "{% set master_html = namespace ~ ':Master:HTML' %}{% extends master_html %}") !== 1 )
            {
                $rawCode = "{% set master_html = namespace ~ ':Master:HTML' %}{% extends master_html %}".$rawCode;
            }
            $template->setContent($rawCode);
        }
        $success = $this->get('persister')->save($template);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.template_readAll'));
    }

    public function saveAction()
    {
        $id = (string)$this->getRequest()->request->get('id');
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $state = (string)$this->getRequest()->request->get('state');
        $name = (string)$this->getRequest()->request->get('name');
        $parent = (string)$this->getRequest()->request->get('parent');
        $type = (string)$this->getRequest()->request->get('type');
        $rawCode = (string)$this->getRequest()->request->get('rawCode');
        $extends = (string)$this->getRequest()->request->get('extends');
        $uses = json_decode((string)$this->getRequest()->request->get('uses'));
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site AND $type !== 'templateTheme' )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        if ($site AND $extends AND ! $site->hasTemplateName($extends) )
        {
            $response = new Response($site->getName().' does not have access to '.$extends);
            $response->setStatusCode(400);
            return $response;
        }
        foreach ($uses as $use) {
            if ( ! $site->hasTemplateName($use) )
            {
                $response = new Response($site->getName().' does not have access to '.$use);
                $response->setStatusCode(400);
                return $response;
            }
        }

        $helper = $this->get('template_helper')->setRawCode($rawCode);
        $result = $helper->createCode($extends, $uses);
        if ( $result['status'] === false )
        {
            $response = new Response($result['message']);
            $response->setStatusCode(400);
            return $response;
        }
        $content = $result['code'];
        $template = $id ? $this->get('persister')->getRepo('CmsCoreBundle:Template')->find($id) : new Template();
        if ( ! $template )
        {
            throw $this->createNotFoundException('Template with id '.$id.' not found');
        }
        if ( $state )
        {
            $template->setState($state);
        }
        if ( $name )
        {
            //in beta version, this should be validated using namespace helper service in combination with type var
            $template->setName($name);
    }
        if ( $parent )
        {
            $template->setParent($parent);
        }
        if ( $content )
        {
            $template->setContent($content);
        }
        $success = $this->get('persister')->save($template, false, null);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        switch($type){
            case 'menu':
                $redirect = $this->generateUrl('cms_core.template_menu', array('siteId' => $siteId));
                break;
            default:
                $redirect = $this->generateUrl('cms_core.template_read', array('id' => $template->getId() ? $template->getId() : $id));
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.template_readAll'));
        }
        return $this->redirect($redirect);
    }

    public function deleteAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $this->get('csrfToken')->validate($token);
        $template = $this->get('persister')->getRepo('CmsCoreBundle:Template')->find($id);
        if ( ! $template )
        {
            throw $this->createNotFoundException('Template with id '.$id.' id not found');
        }
        $success = $this->get('persister')->delete($template);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.template_read', array('id' => $id)));
        }
        return $this->redirect($this->generateUrl('cms_core.template_readAll'));
    }

    public function newAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        return $this->render('CmsCoreBundle:Template:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
        ));
    }

    public function menuAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        $templateName = $site->getName().':Master:HTML';
        $template = $this->get('persister')->getRepo('CmsCoreBundle:Template')->findOneByName($templateName);
        $components = $this->get('template_client')->setCode($template->getContent())->getComponents();
        return $this->render('CmsCoreBundle:Template:menu.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'templateName' => $templateName,
            'template' => $template,
            'code' => $components['rawCode'],
            'extends' => $components['extends'],
            'uses' => $components['uses'],
        ));
    }

}














