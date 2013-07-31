<?php
/**
 * User: Brian Anderson
 * Date: 6/22/13
 * Time: 10:44 AM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Theme;

class ThemeController extends Controller {

    public function readAllAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $themes = $this->get('persister')->getRepo('CmsCoreBundle:Theme')->findAll();
        return $this->render('CmsCoreBundle:Theme:index.html.twig', array(
            'themes' => $themes,
            'token' => $token,
        ));
    }

    public function readAction($id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $theme = $this->get('persister')->getRepo('CmsCoreBundle:Theme')->find($id);
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with id '.$id.' not found');
        }
        return $this->render('CmsCoreBundle:Theme:edit.html.twig', array(
            'token' => $token,
            'theme' => $theme,
        ));
    }

    public function newAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        return $this->render('CmsCoreBundle:Theme:edit.html.twig', array(
            'token' => $token,
        ));
    }

    public function saveAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $id = (string)$this->getRequest()->request->get('id');
        $parentId = (string)$this->getRequest()->request->get('parentId');
        $name = (string)$this->getRequest()->request->get('name');
        $authorName = (string)$this->getRequest()->request->get('authorName');
        $authorImage = (string)$this->getRequest()->request->get('authorImage');
        $authorUrl = (string)$this->getRequest()->request->get('authorUrl');
        $componentTemplateName = (string)$this->getRequest()->request->get('componentTemplateName');
        $layout = $this->getRequest()->request->get('layout');
        $this->get('csrfToken')->validate($token);
        
        $theme = $id ? $this->get('persister')->getRepo('CmsCoreBundle:Theme')->find($id) : new Theme();
        if ( ! $theme )
        {
            throw $this->createNotFoundException('Theme with site id '.$id.' not found');
        }
        if ( $parentId )
        {
            $theme->setParentId($parentId);
        }
        if ( $name )
        {
            $theme->setName($name);
        }
        $author = array();
        if ( $authorName )
        {
            $author['name'] = $authorName;
        }
        if ( $authorImage )
        {
            $author['image'] = $authorImage;
        }
        if ( $authorUrl )
        {
            $author['url'] = $authorUrl;
        }
        if ( ! empty($author) )
        {
            $theme->addAuthor($author);
        }
        if ( $componentTemplateName )
        {
            $theme->setComponentTemplateName($componentTemplateName);
        }
        if ( is_array($layout) )
        {
            foreach ($layout as $layout) {
                $theme->addLayout($layout);
            }
        }
        $success = $this->get('persister')->save($theme);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl(''));
        }
        return $this->redirect($this->generateUrl('cms_core.theme_read', array('id' => $theme->getId())));
    }

    public function deleteAction()
    {
        $token = $this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        if ( ! $theme = $this->get('persister')->getRepo('CmsCoreBundle:Theme')->find($id) )
        {
            throw $this->createNotFoundException('Theme with id '.$id.' not found');
        }
        $this->get('csrfToken')->validate($token);
        $success = $this->get('persister')->delete($theme);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.theme_readAll'));
    }

    public function wizardAction()
    {
        $id = (string)$this->getRequest()->query->get('id');
        $theme = $id ?  $this->get('persister')->getRepo('CmsCoreBundle:Theme')->find($id) : new Theme();
        return $this->render('CmsCoreBundle:Theme:wizard.html.twig', array(
           'theme' =>  $theme,
            'extends' => null, // set dynamically with JS
            'uses' => null, // set dynamically with JS
        ));
    }

}