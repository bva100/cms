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
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site ){
            throw $this->createNotFoundException('Site not found. Be sure the proper siteId was passed.');
        }
        $contentType = $id ? $site->getContentType($id) : new ContentType();
        if ( ! $contentType ){
            throw $this->createNotFoundException('ContentType with id '.$id.' not found');
        }
        $contentType = $this->get('set_contentType')->setRequest($this->getRequest())->setEntity($contentType)->patch();
        if ( ! $id ){
            $site->addContentType($contentType);
        }
        // validate that current user has access to edit sites content types here
        $this->get('persister')->save($site);
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
        $format = (string)$this->getRequest()->query->get('format');
        if ( ! $format )
        {
            $format = 'single';
        }
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
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site )
        {
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        $contentType = $site->getContentType($id);
        if ( ! $contentType )
        {
            throw $this->createNotFoundException('ContentType with id '.$id.' not found');
        }
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndContentTypeAndState($siteId, $contentType->getName(), $state, array(
            'limit' => $limit,
            'offset' => $limit*($page-1),
            'format' => $format,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'tags' => $tagArray,
            'categoryParent' => $categoryParent,
            'categorySub' => $categorySub,
            'authorFirstName' => $authorFirstName,
            'authorLastName' => $authorLastName,
            'search' => $search,
            'sort' => array('by' => $sortBy, 'order' => $sortOrder),
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