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
        $id = (string)$this->getRequest()->request->get('id');
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $domain = (string)$this->getRequest()->request->get('domain');
        $contentTypeId = (string)$this->getRequest()->request->get('contentTypeId'); // for redirect only
        $contentTypeName = (string)$this->getRequest()->request->get('contentTypeName');
        $state = (string)$this->getRequest()->request->get('state');
        $format = (string)$this->getRequest()->request->get('format');
        $locale = (string)$this->getRequest()->request->get('locale');
        $parentNodeId = (string)$this->getRequest()->request->get('parentNodeId');
        $authorFirstName = (string)$this->getRequest()->request->get('authorFirstName');
        $authorLastName = (string)$this->getRequest()->request->get('authorLastName');
        $authorId = (string)$this->getRequest()->request->get('authorId');
        $categories = json_decode((string)$this->getRequest()->request->get('categoriesJSON'));
        $tags = json_decode((string)$this->getRequest()->request->get('tagsJSON'));
        $fields = json_decode((string)$this->getRequest()->request->get('fieldsJSON'));
        $image = (string)$this->getRequest()->request->get('featuredImage');
        $slugPrefix = (string)$this->getRequest()->request->get('slugPrefix');
        $slug = (string)$this->getRequest()->request->get('slug');
        $title = (string)$this->getRequest()->request->get('title');
        $description = (string)$this->getRequest()->request->get('description');
        $templateName = (string)$this->getRequest()->request->get('templateName');
        $viewHtml = (string)$this->getRequest()->request->get('viewHtml');
        $viewText = strip_tags((string)$this->getRequest()->request->get('viewText'));

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
        if ( $domain )
        {
            $node->setDomain($domain);
        }
        if ( $authorFirstName AND $authorLastName AND $authorId )
        {
            $node->setAuthor(array(
                'firstName' => $authorFirstName,
                'lastName' => $authorLastName,
                'id' => $authorId,
            ));
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
        if ( $state )
        {
            $node->setState($state);
        }
        if ( $parentNodeId )
        {
            $node->setParentNodeId($parentNodeId);
        }
        if ( $slug AND $slug !== $node->getSlug() )
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
        if ( $description )
        {
            $node->setDescription($description);
        }
        if ( $templateName )
        {
            $node->setTemplateName($templateName);
        }
        if ( $viewHtml )
        {
            $node->addView('html', $viewHtml);
        }
        if ( $viewText )
        {
            $node->addView('text', $viewText);
        }
        if ( $image )
        {
            $node->setImage($image);
        }
        if ( is_array($categories) AND ! empty($categories) )
        {
            $node->setCategories($categories);
        }
        if ( is_array($tags) AND ! empty($tags) )
        {
            if ( count($tags) === 1 AND $tags[0] === "" )
            {
                $tags = array();
            }
            $node->setTags($tags);
        }
        if ( is_array($fields) AND ! empty($fields) )
        {
            $node->setFields($fields);
        }
        $success = $this->get('persister')->save($node, false, false);
        $nodeId = $success ? $node->getId() : '';
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success, array('onSuccess' => $nodeId));
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        if ( ! $success )
        {
            return $this->redirect($this->generateUrl('cms_core.node_new', array('siteId' => $siteId, 'contentTypeId' => $contentTypeId, 'viewHtml' => $viewHtml, 'title' => $title)));
        }
        return $this->redirect($this->generateUrl('cms_core.node_read', array('id' => $node->getId())));
    }

    public function newAction($siteId, $contentTypeId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $user = $this->get('security.context')->getToken()->getUser();
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' not found');
        }
        $contentType = $site->getContentType($contentTypeId);

        $viewHtml = (string)$this->getRequest()->query->get('viewHtml');
        $title = (string)$this->getRequest()->query->get('title');
        
        return $this->render('CmsCoreBundle:Node:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'user' => $user,
            'node' => null,
            'slug' => null,
            'slugPrefix' => null,
            'isTitleSlug' => true,
            'viewHtml' => $viewHtml,
            'title' => $title,
            'site' => $site,
            'contentType' => $contentType,
        ));
    }

    public function readAction($id)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $user = $this->get('security.context')->getToken()->getUser();
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($id);
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$id.' was not found');
        }
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($node->getSiteId());
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$id.' was not found');
        }
        $contentType = $site->getContentTypeByName($node->getContentTypeName());
        if ( ! $contentType )
        {
            throw $this->createNotFoundException('contentType with id '.$id.' was not found');
        }
        $categories = $contentType->getCategories();
        $nodeCategories = $node->getCategories(false, 'HTML');
        $slugHelper = $this->get('slug_helper')->setFullSlug($node->getSlug())->setTitle($node->getTitle());
        return $this->render('CmsCoreBundle:Node:edit.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'user' => $user,
            'node' => $node,
            'nodeCategories' => $nodeCategories,
            'slug' => $slugHelper->getSlug(),
            'slugPrefix' => $slugHelper->getSlugPrefix(),
            'isTitleSlug' => $slugHelper->isTitleSlug(),
            'site' => $site,
            'contentType' => $contentType,
            'categories' => $categories,
        ));
    }

    public function deleteAction()
    {
//        $this->get('csrfToken')->validate((string)$this->getRequest()->request->get('token'));
        $id = (string)$this->getRequest()->request->get('id');
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($id);
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$id.' not found');
        }
        // ensure user has permission to remove this node
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