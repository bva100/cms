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

    public function readAllAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $site = $this->getSite($siteId);

        $site->addTemplateName('Graphcast:Master:Loop')->addTemplateName('Graphcast:Master:Single')->addTemplateName('Graphcast:Master:Static');
        $success = $this->get('persister')->save($site);
        echo '<pre>', \var_dump('did it work? ', $success); die();
        

        $search = $this->getRequest()->query->get('search');
        $sortBy = (string)$this->getRequest()->query->get('sortBy');
        if ( ! $sortBy )
        {
            $sortBy = 'created';
        }
        $sortOrder = (string)$this->getRequest()->query->get('sortOrder');
        if ( ! $sortOrder )
        {
            $sortOrder = 'desc';
        }
        $limit = $this->getRequest()->query->get('limit');
        if ( ! $limit )
        {
            $limit = 12;
        }
        $page = $this->getRequest()->query->get('page');
        if ( ! $page )
        {
            $page = 1;
        }
        $nextPage = $limit*($page-1) >= $limit ? false : true;
        $templates = $this->get('persister')->getRepo('CmsCoreBundle:Template')->findAllBySiteNamespace($site->getNamespace(), array(
            'search' => $search,
            'sort' => array('by' => $sortBy, 'order' => $sortOrder),
            'limit' => $limit,
            'offset' => $limit*($page-1),
        ));
        return $this->render('CmsCoreBundle:Template:readAll.html.twig', array(
            'token' => $token,
            'templates' => $templates,
            'site' => $site,
            'search' => $search,
            'nextPage' => $nextPage,
            'page' => $page,
        ));
    }

    public function readAction($siteId, $templateId)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('site with id '.$siteId.' not found');
        }
        $template = $this->get('persister')->getRepo('CmsCoreBundle:Template')->find($templateId);
        if ( ! $template )
        {
            throw $this->createNotFoundException('Template with id '.$templateId.' not found');
        }
        $components = $this->get('template_client')->setCode($template->getContent())->getComponents();
        return $this->render('CmsCoreBundle:Template:edit.html.twig', array(
            'template' => $template,
            'site' => $site,
            'code' => $components['rawCode'],
            'extends' => $components['extends'],
            'uses' => $components['uses'],
        ));
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
        $success = $this->get('persister')->save($template, false, false);
        if ( ! $success )
        {
            $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success, array('onSuccess' => $template->getId()));
            if ( $xmlResponse )
            {
                return $xmlResponse;
            }
            else
            {
                return $this->redirect($this->generateUrl('cms_core.template_new'));
            }
            
        }
        $site->addTemplateName($template->getName());
        $success = $this->get('persister')->save($site, false, false);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success, array('onSuccess' => $template->getId()));
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
            return $this->redirect($this->generateUrl('cms_core.template_new'));
        }
        return $this->redirect($redirect);
    }

    public function deleteAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $site = $this->getSite($siteId);
        $templateId = (string)$this->getRequest()->request->get('templateId');
        $template = $this->get('persister')->getRepo('CmsCoreBundle:Template')->find($templateId);
        if ( ! $template )
        {
            throw $this->createNotFoundException('Template with id '.$id.' id not found');
        }
        if ( ! $site->hasTemplateName($template->getName()) )
        {
            throw new \Exception('You do not have access to detele this template');
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

    public function newAction($siteId)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('site with id '.$siteId.' not found');
        }
        return $this->render('CmsCoreBundle:Template:edit.html.twig', array(
            'site' => $site,
            'status' => 'new',
            'code' => null,
        ));
    }

    public function menuAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        $templateName = $site->getNamespace().':Master:HTML';
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

    public function getSite($siteId)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        return $site;
    }
}














