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

    public function deleteAction()
    {
        $siteId = (string)$this->getRequest()->request->get('siteId');
        $contentTypeId = (string)$this->getRequest()->request->get('contentTypeId');
        $site = $this->getSite($siteId);
        $contentType = $this->getContentType($site, $contentTypeId);
        $site->removeContentType($contentType);
        $success = $this->get('persister')->save($site);
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
            throw $this->createNotFoundException('Content type with id'.$contentTypeId.' not found');
        }
        return $contentType;
    }

}