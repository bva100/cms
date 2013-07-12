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
        $id = (string)$this->getRequest()->request->get('id');
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $name = (string)$this->getRequest()->request->get('name');
        $slugPrefix = (string)$this->getRequest()->request->get('slugPrefix');
        $formatType = (string)$this->getRequest()->request->get('format');
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
        $state = $this->getRequest()->query->get('state');
        $startDate = $this->getRequest()->query->get('startDate');
        $endDate = $this->getRequest()->query->get('endDate');
        $categoryParent = $this->getRequest()->query->get('categoryParent');
        $categorySub = $this->getRequest()->query->get('categorySub');
        $authorFirstName = $this->getRequest()->query->get('authorFirstName');
        $authorLastName = $this->getRequest()->query->get('authorLastName');
        $search = $this->getRequest()->query->get('search');
        $tags = $this->getRequest()->query->get('tags');
        $tagArray = $tags ? explode(',', $tags) : array();
        $page = $this->getRequest()->query->get('page');
        if ( ! $page )
        {
            $page = 1;
        }
        $nextPage = 5*($page-1) >= 5 ? false : true ;
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
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndContentTypeAndState($siteId, $contentType->getName(), $state, array(
            'limit' => 5,
            'offset' => 5*($page-1),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'tags' => $tagArray,
            'categoryParent' => $categoryParent,
            'categorySub' => $categorySub,
            'authorFirstName' => $authorFirstName,
            'authorLastName' => $authorLastName,
            'search' => $search,
        ));
        return $this->render('CmsCoreBundle:ContentType:read.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'page' => $page,
            'nextPage' => $nextPage,
            'state' => $state,
            'contentType' => $contentType,
            'nodes' => $nodes,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'categoryParent' => $categoryParent,
            'categorySub' => $categorySub,
            'tags' => $tags,
            'authorFirstName' => $authorFirstName,
            'authorLastName' => $authorLastName,
            'search' => $search,
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

    public function addCategoryAction()
    {
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $id = (string)$this->getRequest()->request->get('id');
        $parent = (string)$this->getRequest()->request->get('parent');
        if ( ! $parent )
        {
            throw new \Exception('Parent is required for a new category');
        }
        $sub = (string)$this->getRequest()->request->get('sub');
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        $contentType = $site->getContentType($id);
        if ( ! $contentType )
        {
            throw $this->createNotFoundExcpetion('Content type with id '.$id.' not found');
        }
        $contentType->addCategory($parent, $sub ? $sub : null, true);
        $success = $this->get('persister')->save($site);
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.contentType_read', array('siteId' => $siteId, 'id' => $id) ));
    }

}