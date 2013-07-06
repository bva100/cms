<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 2:28 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Site;

class SiteController extends Controller {

    public function saveAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $name = (string)$this->getRequest()->request->get('name');
        $this->get('csrfToken')->validate($token);

        $site = $id ? $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($id) : new Site();
        if ( ! $site )
        {
            return $this->createNotFoundException('Site not found');
        }
        if ( $name )
        {
            $site->setName($name);
            $site->setNamespace($name);
        }
        $site->addDomain('skiblogfoobar.com');
        $success = $this->get('persister')->save($site);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.app_index'));
        }
        return $this->redirect($this->generateUrl('cms_core.site_read', array('id' => $site->getId())));
    }

    public function settingsAction($id)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($id);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        // ensure user has access to sites settings
        $contentTypes = $site->getContentTypes();
        return $this->render('CmsCoreBundle:Site:settings.html.twig', array(
            'site' => $site,
            'contentTypes' => $contentTypes,
        ));
    }

    public function readAction($id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($id);
        $contentTypes = $site->getContentTypes();
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteId($id);
        return $this->render('CmsCoreBundle:Site:index.html.twig', array(
            'site' => $site,
            'token' => $token,
            'notices' => $notices,
            'contentTypes' => $contentTypes,
            'nodes' => $nodes,
        ));
    }

    public function deleteAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($id);
        $this->get('csrfToken')->validate($token);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        // ensure use has proper permission to delete this site
        $success = $this->get('persister')->delete($site);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.app_index'));
    }

}