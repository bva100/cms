<?php


namespace Cms\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cms\CoreBundle\Document\ContentType;

class TypesController extends Controller{

    public function readAllAction($siteId)
    {
        $site = $this->getSite($siteId);
        $types = $site->getContentTypes();
        return $this->render('CmsCoreBundle:Types:readAll.html.twig', array(
            'site' => $site,
            'types' => $types,
        ));
    }

    public function newAction($siteId)
    {
        $site = $this->getSite($siteId);
        return $this->render('CmsCoreBundle:Types:edit.html.twig', array(
            'site' => $site,
            'notices' => null,
        ));
    }

    public function readAction($siteId, $id)
    {
        $notices = $this->get('session')->getFlashBag()->get('notices');
        $site = $this->getSite($siteId);
        $type = $site->getContentType($id);
        if ( ! $type ){
            throw $this->createNotFoundException('Type with id '.$id.' does not exist.');
        }
        return $this->render('CmsCoreBundle:Types:edit.html.twig', array(
            'site' => $site,
            'type' => $type,
            'notices' => $notices,
        ));
    }

    public function saveAction()
    {
        $siteId = $this->getRequest()->request->get('siteId');
        $id = $this->getRequest()->request->get('id');
        $name = $this->getRequest()->request->get('name');
        $slugPrefix = $this->getRequest()->request->get('slugPrefix');
        $description = $this->getRequest()->request->get('description');
        $site = $this->getSite($siteId);
        if ( $id ){
            $type = $site->getContentType($id);
        }else{
            $type = new ContentType();
        }
        if ( ! $type ){
            throw $this->createNotFoundException('Content Type with id '.$id.' not found');
        }
        if ( isset($name) ){
            $type->setName($name);
        }
        if ( isset($slugPrefix) ){
            $type->setSlugPrefix($slugPrefix);
        }
        if ( isset($description) ){
            $type->setDescription($description);
        }
        if ( ! $id ){
            $site->addContentType($type);
        }
        $success = $this->get('persister')->save($type);
        $id = $type->getId();
        if ( ! $success ){
            throw new RuntimeExcpetion('Unable to save Type resource at this time. Please try again.');
        }
        return $this->redirect($this->generateUrl('cms_core.types_read', array('siteId' => $siteId, 'id' => $id)));
    }

    public function deleteAction()
    {
        $siteId = $this->getRequest()->request->get('siteId');
        $site = $this->getSite($siteId);
        $id = $this->getRequest()->request->get('id');
        $type = $site->getContentType($id);
        if ( ! $type ){
            throw $this->createNotFoundException('Unable to find content Type with id '.$id);
        }
        $site->removeContentType($type);
        $success = $this->get('persister')->save($site);
        if ( ! $success ){
            throw new Exception('Unable to delete this Type at this time. Please try again.');
        }
        return $this->redirect($this->generateUrl('cms_core.types_readAll', array('siteId' => $siteId)));
    }

    public function getSite($siteId)
    {
        $site = $this->get('persister')->getRepo('CmsCoreBundle:Site')->find($siteId);
        if ( ! $site ){
            throw $this->createNotFoundException('Site with id '.$siteId.' not found');
        }
        return $site;
    }

}