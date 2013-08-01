<?php
/**
 * User: Brian Anderson
 * Date: 7/31/13
 * Time: 6:56 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use \Cms\CoreBundle\Document\ThemeOrg;
use \Cms\CoreBundle\Document\Theme;

class ThemeOrgController extends Controller{

    public function newAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        return $this->render('CmsCoreBundle:ThemeOrg:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
        ));
    }

    public function readAction($id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($id);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme organization with id '.$id.' not found');
        };
        return $this->render('CmsCoreBundle:ThemeOrg:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'themeOrg' => $themeOrg,
            'themes' => $themeOrg->getThemes(),
        ));
    }

    public function saveAction()
    {
//        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $organization = (string)$this->getRequest()->request->get('organization');
        $namespace = (string)$this->getRequest()->request->get('namespace');
        $website = (string)$this->getRequest()->request->get('website');

        $themeOrg = $id ? $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($id) : new ThemeOrg();
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme organization with id '.$id.' not found');
        }
        if ( $organization )
        {
            $themeOrg->setOrganization($organization);
        }
        if ( $namespace )
        {
            $themeOrg->setNamespace($namespace);
        }
        if ( $website )
        {
            $themeOrg->setWebsite($website);
        }
        $success = $this->get('persister')->save($themeOrg);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success AND ! $id )
        {
            return $this->redirect($this->generateUrl('cms_core.themeOrg_new'));
        }
        return $this->redirect($this->generateUrl('cms_core.themeOrg_read', array('id' => $themeOrg->getId())));
    }

    public function deleteAction()
    {
        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $themeOrg = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->find($id);
        if ( ! $themeOrg )
        {
            throw $this->createNotFoundException('Theme Organization with id '.$id.' not found');
        }
        // ensure use has access to delete this theme org
        $success = $this->get('persister')->delete($themeOrg);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.themeOrg_readAll'));
    }

    public function readAllAction()
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $themeOrgs = $this->get('persister')->getRepo('CmsCoreBundle:ThemeOrg')->findAll();
        return $this->render('CmsCoreBundle:ThemeOrg:readAll.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'themeOrgs' => $themeOrgs,
        ));
    }

}