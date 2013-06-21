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

    public function saveAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $state = (string)$this->getRequest()->request->get('state');
        $name = (string)$this->getRequest()->request->get('name');
        $parent = (string)$this->getRequest()->request->get('parent');
        $content = (string)$this->getRequest()->request->get('content');
//        $this->get('csrfToken')->validate($token);

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
        $success = $this->get('persister')->save($template);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.template_read', array('id' => $template->getId())));
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

}