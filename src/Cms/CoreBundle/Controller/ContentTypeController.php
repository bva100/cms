<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 11:52 PM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\ContentType;

class ContentTypeController extends Controller {

    public function saveAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $name = (string)$this->getRequest()->request->get('name');
        $slugPrefix = (string)$this->getRequest()->request->get('slugPrefix');
        $formatType = (string)$this->getRequest()->request->get('format');
        $this->get('csrfToken')->validate($token);

        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site not found. Be sure the proper siteId was passed.');
        }
        // validate that current user has access to edit sites content types here

        $contentType = $id ? $site->getContentType($id) : new ContentType();
        if ( $name )
        {
            $contentType->setName($name);
        }
        if ( $slugPrefix )
        {
            $contentType->setSlugPrefix($slugPrefix);
        }
        if ( $formatType === 'static' )
        {
            $contentType->addFormat('static');
        }
        else if($formatType === 'dynamic')
        {
            $contentType->addFormat('single');
            $contentType->addFormat('loop');
        }
        $site->addContentType($contentType);
        $success = $this->get('persister')->save($site);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.site_read', array('id' => $siteId)));
    }

    public function settingsAction($siteId, $id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        $contentType = $site->getContentType($id);
        if ( ! $contentType )
        {
            throw $this->createNotFoudnExcpetion('Content type with id '.$id.' not found');
        }
        return $this->render('CmsCoreBundle:ContentType:settings.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'contentType' => $contentType,
        ));
    }

    public function readAction($siteId, $id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $page = $this->getRequest()->query->get('page');
        if ( ! $page )
        {
            $page = 1;
        }
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        $contentType = $site->getContentType($id);
        if ( ! $contentType )
        {
            throw $this->createNotFoundException('ConentType with id '.$id.' not found');
        }
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndContentType($siteId, $contentType->getName(), array(
            'limit' => 5,
            'offset' => 5*($page-1),
//            'tags' => explode(',', 'dogs,behavior'),
        ));
        return $this->render('CmsCoreBundle:ContentType:read.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'page' => $page,
            'site' => $site,
            'contentType' => $contentType,
            'nodes' => $nodes,
        ));
    }

    public function deleteAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $this->get('csrfToken')->validate($token);

        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        $contentType = $site->getContentType($id);
        if ( ! $contentType )
        {
            throw $this->createNotFoundException('Content type with id '.$id.' not found');
        }
        // ensure user has permission to delete contentType
        $site->removeContentType($contentType);
        $success = $this->get('persister')->save($site);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.contentType_read', array('id' => $id, 'siteId' => $siteId)));
        }
        return $this->redirect($this->generateUrl('cms_core.site_read', array('id' => $siteId)));
    }

}