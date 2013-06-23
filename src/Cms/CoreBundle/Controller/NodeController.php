<?php
/**
 * User: Brian Anderson
 * Date: 6/18/13
 * Time: 9:51 AM
 */

namespace Cms\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\Node;

class NodeController extends Controller {

    public function saveAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $host = (string)$this->getRequest()->request->get('host');
        $contentTypeId = (string)$this->getRequest()->request->get('contentTypeId'); // for redirect only
        $contentTypeName = (string)$this->getRequest()->request->get('contentTypeName');
        $format = (string)$this->getRequest()->request->get('format');
        $locale = (string)$this->getRequest()->request->get('locale');
        $parentNodeId = (string)$this->getRequest()->request->get('parentNodeId');
        //left off on categories
        $slugPrefix = (string)$this->getRequest()->request->get('slugPrefix');
        $slug = (string)$this->getRequest()->request->get('slug');
        $title = (string)$this->getRequest()->request->get('title');
        $templateName = (string)$this->getRequest()->request->get('templateName');
        $viewHtml = (string)$this->getRequest()->request->get('viewHtml');
        // $this->get('csrfToken')->validate($token);

        // validate user has access to change and add new nodes
        $node = $id ? $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($id) : new Node();
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$id.' not found');
        }
        if ( $siteId )
        {
            $node->setSiteId($siteId);
        }
        if ( $host )
        {
            // validate user and site have access to this host
            $node->setHost($host);
        }
        if ( $contentTypeName )
        {
            $node->setContentTypeName($contentTypeName);
        }
        if ( $format )
        {
            $node->setFormat($format);
        }
        if ( $locale )
        {
            $node->setLocale($locale);
        }
        if ( $parentNodeId )
        {
            $node->setParentNodeId($parentNodeId);
        }
        if ( $slug )
        {
            if ( $slugPrefix )
            {
                $slug = $slugPrefix.$slug;
            }
            $node->setSlug($slug);
        }
        if ( $title )
        {
            $node->setTitle($title);
        }
        if ( $templateName )
        {
            $node->setTemplateName($templateName);
        }
        if ( $viewHtml )
        {
            $node->addView('html', $viewHtml);
        }
        $node->addJavascript('<script>alert("hello scriptkiddie workd")</script>');
        $success = $this->get('persister')->save($node);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.node_new', array('siteId' => $siteId, 'contentTypeId' => $contentTypeId)));
        }
        return $this->redirect($this->generateUrl('cms_core.site_read', array('id' => $siteId)));


    }

    public function newAction($siteId, $contentTypeId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        $contentType = $site->getContentType($contentTypeId);
        return $this->render('CmsCoreBundle:Node:new.html.twig', array(
            'site' => $site,
            'token' => $token,
            'notices' => $notices,
            'contentType' => $contentType,
        ));
    }

    public function deleteAction()
    {
        $token = (string)$this->getRequest()->request->get('token');
        $id = (string)$this->getRequest()->request->get('id');
        $this->get('csrfToken')->validate($token);

        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($id);
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$id.' not found');
        }
        // ensure use has permission to remove this node
        $success = $this->get('persister')->delete($node);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.node_read', array('id' => $id)));
        }
        return $this->redirect($this->generateUrl('cms_core.site_read', array('id' => $node->getSiteId())));
        
    }

}