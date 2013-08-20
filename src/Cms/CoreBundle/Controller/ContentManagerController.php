<?php
/**
 * User: Brian Anderson
 * Date: 6/17/13
 * Time: 11:52 PM
 */

namespace Cms\CoreBundle\Controller;

use Cms\CoreBundle\Document\Node;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\ContentType;

class ContentManagerController extends Controller {

    public function readAllAction($siteId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->getSite($siteId);
        return $this->render('CmsCoreBundle:ContentManager:index.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
        ));
    }

    public function staticReadAllAction($siteId)
    {
        $site = $this->getSite($siteId);
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
        $nodes = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findBySiteIdAndContentTypeAndState($siteId, null, null, array(
            'limit' => $limit,
            'offset' => $limit*($page-1),
            'format' => 'static',
            'search' => $search,
            'sort' => array('by' => $sortBy, 'order' => $sortOrder),
        ));
        return $this->render('CmsCoreBundle:ContentManager:static.html.twig', array(
            'site' => $site,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'limit' => $limit,
            'page' => $page,
            'nextPage' => $nextPage,
            'nodes' => $nodes,
        ));
    }

    public function wizardAction($siteId)
    {
        $contentTypeId = $this->getRequest()->query->get('contentTypeId');
        $site = $this->getSite($siteId);
        $contentType = $contentTypeId ? $this->getContentType($site, $contentTypeId) : null;
        return $this->render('CmsCoreBundle:ContentManager:wizard.html.twig', array(
            'site' => $site,
            'contentType' => $contentType,
        ));
    }

    public function saveBasicsAction($siteId)
    {
        $contentTypeId = (string)$this->getRequest()->request->get('contentTypeId');
        $name = (string)$this->getRequest()->request->get('name');
        $slugPrefix = (string)$this->getRequest()->request->get('slugPrefix');
        $description = (string)$this->getRequest()->request->get('description');
        $site = $this->getSite($siteId);

        $contentType = $contentTypeId ? $site->getContentType($contentTypeId) : new ContentType();
        if ( ! $contentType )
        {
            throw $this->createNotFoundException('Content Type with id '.$contentTypeId.' not found');
        }
        if ( $name )
        {
            $contentType->setName($name);
        }
        if ( $slugPrefix )
        {
            $contentType->setSlugPrefix($slugPrefix);
        }
        if ( $description )
        {
            $contentType->setDescription($description);
        }
        if ( ! $contentTypeId )
        {
            $site->addContentType($contentType);
        }
        return $this->get('xmlResponse')->execute($this->getRequest(), $this->get('persister')->save($site), array('onSuccess' => $contentType->getId()));
    }

    public function formatsAction($siteId, $contentTypeId)
    {
        $token = $this->get('csrfToken')->createToken()->getToken();
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->getSite($siteId);
        $contentType = $this->getContentType($site, $contentTypeId);
        return $this->render('CmsCoreBundle:ContentManager:wizardFormats.html.twig', array(
            'token' => $token,
            'notices' => $notices,
            'site' => $site,
            'contentType' => $contentType,
        ));
    }

    public function saveFormatsAction($siteId, $contentTypeId)
    {
        $site = $this->getSite($siteId);
        $contentType = $this->getContentType($site, $contentTypeId);
        $formatType = (string)$this->getRequest()->request->get('formatType');
        switch ($formatType){
            case 'static':
                $contentType->setFormats(array('static'));
                break;
            case 'dynamic':
                $contentType->setFormats(array('single', 'loop'));
                break;
            default:
                break;
        }
        $success = $this->get('persister')->save($site);
        return $this->get('xmlResponse')->execute($this->getRequest(), $success);
    }

    public function loopAction($siteId, $contentTypeId)
    {
        $site = $this->getSite($siteId);
        $nodeId = (string)$this->getRequest()->query->get('nodeId');
        $domain = (string)$this->getRequest()->query->get('domain');
        $locale = (string)$this->getRequest()->query->get('locale');
        $slug = (string)$this->getRequest()->query->get('slug');
        $newLoop = (bool)$this->getRequest()->query->get('newLoop');
        $contentType = $this->getContentType($site, $contentTypeId);
        if ( $nodeId ){
            $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($nodeId);
        }else if($domain AND $slug){
            $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findOneByDomainAndLocaleAndSlug($domain, $locale ? $locale : null, $slug);
        }else{
            $node = null;
        }
        $loops = $contentType->getLoops();

        return $this->render('CmsCoreBundle:ContentManager:wizardLoop.html.twig', array(
            'site' => $site,
            'contentType' => $contentType,
            'node' => $node,
            'loops' => $loops,
            'newLoop' => $newLoop,
        ));
    }

    public function saveLoopAction($siteId, $contentTypeId)
    {
        $nodeId = (string)$this->getRequest()->request->get('nodeId');
        $domain = (string)$this->getRequest()->request->get('domain');
        $locale = (string)$this->getRequest()->request->get('locale');
        $slug = (string)$this->getRequest()->request->get('slug');
        $title = (string)$this->getRequest()->request->get('title');
        $defaultLimit = (int)$this->getRequest()->request->get('defaultLimit');
        $description = (string)$this->getRequest()->request->get('description');
        $site = $this->getSite($siteId);
        $contentType = $this->getContentType($site, $contentTypeId);
        $node = $nodeId ? $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($nodeId) : new Node();
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$nodeId.' not found');
        }
        $node->setSiteId($siteId);
        $node->setContentTypeName($contentType->getName());
        $node->setFormat('loop');
        $node->setTemplateName($site->getNamespace().':Master:Loop'); // this can be set to something custom for custom loops which, perhaps, extend Master:Loop
        $node->setState('active');
        if ( $domain )
        {
            $node->setDomain($domain);
        }
        if ( $locale )
        {
            $node->setLocale($locale);
        }
        if ( $slug )
        {
            $node->setSlug($slug);
        }
        if ( $title )
        {
            $node->setTitle($title);
        }
        if ( $defaultLimit )
        {
            $node->setDefaultLimit($defaultLimit);
        }
        if ( $description )
        {
            $node->setDescription($description);
        }
        $success = $this->get('persister')->save($node, false, false);
        if ( ! $success  )
        {
            return $this->get('xmlResponse')->execute($this->getRequest(), $success);
        }
        $contentType->addLoop($node->getId(), $node->getDomain(), $node->getLocale(), $node->getSlug());
        $success = $this->get('persister')->save($site, false, false);
        return $this->get('xmlResponse')->execute($this->getRequest(), $success);
    }

    public function deleteLoopAction($siteId, $contentTypeId)
    {
        $nodeId = (string)$this->getRequest()->request->get('nodeId');
        $site = $this->getSite($siteId);
        $contentType = $this->getContentType($site, $contentTypeId);
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($nodeId);
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$nodeId.' not found');
        }
        $contentType->removeLoop($nodeId);
        $success = $this->get('persister')->save($site);
        if ( ! $success )
        {
            return $this->get('xmlResponse')->execute($this->getRequest(), $success);
        }
        $success = $this->get('persister')->delete($node);
        return $this->get('xmlResponse')->execute($this->getRequest(), $success);
    }

    public function staticAction($siteId, $contentTypeId)
    {
        $site = $this->getSite($siteId);
        $contentTypeName = (string)$this->getRequest()->query->get('contentTypeName');
        if ( $contentTypeId === 'null' AND $contentTypeName ){
            $contentType = $this->getContentTypeByName($site, $contentTypeName);
        }else{
            $contentType = $this->getContentType($site, $contentTypeId);
        }
        $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->findOneBySiteIdAndContentTypeNameAndFormat($siteId, $contentType->getName(), 'static');
        if ( ! $node )
        {
            $node = null;
        }
        return $this->render('CmsCoreBundle:ContentManager:wizardStatic.html.twig', array(
            'site' => $site,
            'contentType' => $contentType,
            'node' => $node,
        ));
    }

    public function saveStaticAction($siteId, $contentTypeId)
    {
        $nodeId = (string)$this->getRequest()->request->get('nodeId');
        $domain = (string)$this->getRequest()->request->get('domain');
        $locale = (string)$this->getRequest()->request->get('locale');
        $slug = (string)$this->getRequest()->request->get('slug');
        $title = (string)$this->getRequest()->request->get('title');
        $description = (string)$this->getRequest()->request->get('description');
        $content = (string)$this->getRequest()->request->get('content');
        $site = $this->getSite($siteId);
        $contentType = $this->getContentType($site, $contentTypeId);
        $node = $nodeId ? $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($nodeId) : new Node();
        if ( ! $node )
        {
            throw $this->createNotFoundException('Node with id '.$nodeId.' not found');
        }
        $node->setSiteId($siteId);
        $node->setContentTypeName($contentType->getName());
        $node->setFormat('static');
        $node->setTemplateName($site->getNamespace().':Master:Static'); // this can be set to something custom for custom static pages which, perhaps, extend Master:Static
        $node->setState('active');
        if ( $domain )
        {
            $node->setDomain($domain);
        }
        if ( $locale )
        {
            $node->setLocale($locale);
        }
        if ( $slug )
        {
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
        if ( $content )
        {
            $node->addView('html', $content);
        }
        $success = $this->get('persister')->save($node, false, false);
        return $this->get('xmlResponse')->execute($this->getRequest(), $success);
    }

    public function deleteAction()
    {
        // this methods needs some serious access control
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $contentTypeId = (string)$this->getRequest()->request->get('contentTypeId');
        $contentTypeName = (string)$this->getRequest()->request->get('contentTypeName');
        $nodeId = (string)$this->getRequest()->request->get('nodeId');
        $site = $this->getSite($siteId);
        if ( ! $contentTypeId AND $contentTypeName ){
            $contentType = $this->getContentTypeByName($site, $contentTypeName);
        }else{
            $contentType = $this->getContentType($site, $contentTypeId);
        }
        if ( $nodeId )
        {
            $node = $this->get('persister')->getRepo('CmsCoreBundle:Node')->find($nodeId);
            if ( ! $node )
            {
                throw $this->createNotFoundException('Node id with '.$nodeId.' not found');
            }
        }
        $site->removeContentType($contentType);
        $success = $this->get('persister')->save($site);
        if ( $success AND $nodeId )
        {
            $this->get('persister')->delete($node);
        }
        $xmlResponse = $this->get('xmlResponse')->execute($this->getRequest(), $success);
        if ( $xmlResponse )
        {
            return $xmlResponse;
        }
        return $this->redirect($this->generateUrl('cms_core.content_manager_readAll', array('siteId' => $siteId)));
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

    public function getContentType($site, $contentTypeId)
    {
        $contentType = $site->getContentType($contentTypeId);
        if ( ! $contentType )
        {
            throw $this->createNotFoundException('Content type with id '.$contentTypeId.' not found');
        }
        return $contentType;
    }

    public function getContentTypeByName($site, $name)
    {
        $contentType = $site->getContentTypeByName($name);
        if ( ! $contentType )
        {
            throw $this->createNotFoundException('Content type with name '.$name.' not found');
        }
        return $contentType;
    }

}